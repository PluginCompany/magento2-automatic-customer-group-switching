<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Observer;

use Magento\Framework\Event\Observer;

class CustomerSaveBefore extends AbstractObserver
{
    private $customer;

    public function execute(Observer $observer)
    {
        if($this->_isCron()) return;
        if($this->_isBulk()) return;

        try {
            $this->customer = $observer->getEvent()->getCustomer();
            if(!$this->customer || !$this->customer->getId()) return;

            if(!$this->alreadySwitched()){
                $this->processRules();
            }
            $this->setNewGroupIdToCustomer();
        } catch (\Exception $e){
            $this->logger->critical($e->getMessage());
        }
    }

    private function alreadySwitched()
    {
        return $this->frameworkRegistry->registry('customer_group_switched');
    }

    private function processRules()
    {
        //register to prevent infinite loop
        $this->frameworkRegistry
            ->register('customer_group_switched', true);
        //execute rules
        $this->ruleFactory->create()
            ->processRules($this->customer,'customer_save');
        return $this;
    }

    private function setNewGroupIdToCustomer()
    {
        if($this->getNewGroupId()){
            $this->customer->setGroupId($this->getNewGroupId());
        }
        return $this;
    }

    private function getNewGroupId()
    {
        return $this->frameworkRegistry
            ->registry('pc_new_customer_group_id');
    }
}