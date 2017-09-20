<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\DataObject;

class SalesOrderSaveAfter extends AbstractObserver
{
    public function execute(Observer $observer)
    {
        try {
            $customer = $observer->getEvent()->getOrder()->getCustomer();
            if(!$customer){
                $customer = new DataObject();
            }
            if (!$customer->getId() && $observer->getEvent()->getOrder()->getCustomerId()) {
                $customer = $this->customerRegistry->retrieve(
                    $observer->getEvent()->getOrder()->getCustomerId()
                );
            }elseif(!$customer->getId()){
                return;
            }
            $order = $observer->getEvent()->getOrder();
            //register to prevent infinite loop
            if (!$this->frameworkRegistry->registry('customer_group_switched')) {
                $this->frameworkRegistry->register('customer_group_switched', true);
            }
            //execute rules
            $this->ruleFactory->create()->processRules($customer,'order_save',$order, true);
        } catch (\Throwable $e){
            $this->logger->critical($e->getMessage());
        }
    }
}
