<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Model\Rule\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class Events implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $events = [
            [
                'label' => 'Customer account creation & saving',
                'value' => 'customer_save'
            ],
            [
                'label' => 'Customer account log in',
                'value' => 'customer_login'
            ],
            [
                'label' => 'Order creation & saving',
                'value' => 'order_save'
            ],
            [
                'label' => 'Invoice creation & saving',
                'value' => 'invoice_save'
            ],
            [
                'label' => 'Default billing / shipping address saving',
                'value' => 'address_save'
            ],
            [
                'label' => 'Newsletter subscription creation & saving',
                'value' => 'newsletter_subscriber_save'
            ],
            [
                'label' => 'Cronjob group switching batch',
                'value' => 'customer_cron_job'
            ],
        ];
        return $events;
    }
}
