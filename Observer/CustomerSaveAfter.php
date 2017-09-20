<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Observer;

use Magento\Framework\Event\Observer;

class CustomerSaveAfter extends AbstractObserver
{
    public function execute(Observer $observer)
    {
        if($this->_isCron()) return;
        if($this->_isBulk()) return;

        try {
            $customer = $observer->getEvent()->getCustomer();
            if(!$customer) return;
            if ($customer->getId() && !$this->frameworkRegistry->registry('customer_group_switched')) {
                //register to prevent infinite loop
                $this->frameworkRegistry->register('customer_group_switched', true);
                //exectute rules
                $this->ruleFactory->create()->processRules($customer,'customer_save',null,true);
            }
        } catch (\Exception $e){
            $this->logger->critical($e->getMessage());
        }
    }
}
