<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Observer;

use Magento\Customer\Model\Data\Customer as CustomerDataModel;
use Magento\Framework\Event\Observer;

class CustomerLogin extends AbstractObserver
{
    public function execute(Observer $observer)
    {
        $this->logger->debug('customer login event');

        try {
            $customer = $this->getCustomerModelFromObserver($observer);

            if(!$customer) return;

            $this->logger->debug('customer is ' . $customer->getEmail());


            if ($customer->getId() && !$this->frameworkRegistry->registry('customer_group_switched')) {
                //register to prevent infinite loop
                $this->frameworkRegistry->register('customer_group_switched', true);
                //execute rules
                $this->ruleFactory->create()->processRules($customer,'customer_login',null,true);
            }
        } catch (\Throwable $e){
            $this->logger->critical($e->getMessage());
        }
    }

    private function getCustomerModelFromObserver(Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        if($customer instanceof CustomerDataModel) {
            $customer = $this->customerRegistry->retrieve($customer->getId());
        }
        if(!$customer->getId()) {
            return false;
        }
        return $customer;
    }

}
