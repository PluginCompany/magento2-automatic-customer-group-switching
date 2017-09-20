<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Observer;

use Magento\Framework\Event\Observer;

class SalesOrderSaveBefore extends AbstractObserver
{
    public function execute(Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();
            if(!$order->getConvertingFromQuote()) return;

            $quote = $order->getQuote();
            $groupId = $quote->getCustomerGroupId();
            if(!$groupId) return;

            $order->setCustomerGroupId($groupId);
        } catch (\Exception $e){
            $this->logger->critical($e->getMessage());
        }
    }
}
