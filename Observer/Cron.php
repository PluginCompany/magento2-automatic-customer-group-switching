<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Observer;

use Magento\Framework\Event\Observer;

class Cron extends AbstractObserver
{
    public function execute(Observer $observer)
    {
        return $this->run();
    }
    public function run()
    {
        try {
            // add registry value to make sure rules
            // aren't executed again when customer is saved
            if(!$this->frameworkRegistry->registry('is_groupswitch_cron')){
                $this->frameworkRegistry->register('is_groupswitch_cron', true);
            }

            $cronFlag = $this->cronflagFactory->create()->loadSelf();
            $lastCustomerId = $cronFlag->getLastCustomerId();
            $runOnce = $this->scopeConfig->isSetFlag('groupswitch/cronjob/only_once');

            //check if all customers are already processed today
            if($runOnce && $cronFlag->hasProcessedToday() && $lastCustomerId === 0){
                return;
            }

            //get customer collection
            $customersCollection = $this->customerFactory->create()->getCollection();

            //continue where last cronjob left off
            if($lastCustomerId){
                $customersCollection
                    ->addFieldToFilter('entity_id',['gt' => $lastCustomerId]);
            }

            $customersCollection->setPageSize(50);

            //set max pages per cronjob
            $limit = $this->scopeConfig->getValue('groupswitch/cronjob/max_customer');
            if($limit){
                $pages = $limit / 50;
            }else{
                $pages = $customersCollection->getLastPageNumber();
            }

            $currentPage = 1;

            do {
                $customersCollection->setCurPage($currentPage);
                $customersCollection->load();

                foreach ($customersCollection as $customer) {
                    try {
                        $this->ruleFactory->create()->processRules($customer, 'customer_cron_job', null, true);
                        $lastCustomerId = $customer->getId();
                    }catch(\Exception $e){
                        $this->logger->critical($e->getMessage());
                    }
                }

                //if less than 50 left (one page), all customers are processed
                if($customersCollection->count() < 50){
                    $cronFlag->saveCustomerIdAndTimestamp(0);
                }else{
                    $cronFlag->saveCustomerIdAndTimestamp($lastCustomerId);
                }

                $currentPage++;
                //clear collection and free memory
                $customersCollection->clear();
            } while ($currentPage <= $pages);
        } catch (\Throwable $e){
            $this->logger->critical($e->getMessage());
        }
    }
}
