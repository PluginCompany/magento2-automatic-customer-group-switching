<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Observer;

use Magento\Framework\Event\Observer;

class NewsletterSubscriberSaveAfter extends AbstractObserver
{
    public function execute(Observer $observer)
    {
        try {
            $subscriber = $observer->getEvent()->getSubscriber();
            //check if connected to customer account
            if (!$subscriber->getCustomerId()) {
                return;
            }

            $customer = $this->customerRegistry->retrieve(
                $subscriber->getCustomerId()
            );

            //register to prevent infinite loop
            if (!$this->frameworkRegistry->registry('customer_group_switched')) {
                $this->frameworkRegistry->register('customer_group_switched', true);
            }
            //execute rules
            $this->ruleFactory->create()->processRules($customer, 'newsletter_subscriber_save', null, true);

        } catch (\Throwable $e){
            $this->logger->critical($e->getMessage());
        }
    }
}
