<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */

/**
 * Class \PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Order
 */
namespace PluginCompany\CustomerGroupSwitching\Model\Rule\Condition;

use Magento\Framework\DataObject;

class Order extends Totals
{

    protected $_typeName = 'order';

    /**
     * Set attribute options
     *
     * @return $this
     */
    public function loadAttributeOptions()
    {
        $attributes = [
            'order_base_subtotal_incl_tax' => __('Subtotal (incl. Tax)'),
            'order_base_subtotal' => __('Subtotal (excl. Tax)'),
            'order_base_grand_total' => __('Grand Total (incl. Tax)'),
            'order_base_total_invoiced' => __('Total Invoiced (incl. Tax)'),
            'order_base_total_invoiced_ex_tax' => __('Total Invoiced (excl. Tax)'),
            'order_base_total_invoiced_minus_refunded' => __('Total Invoiced - Total Refunded (incl. Tax)'),
            'order_base_total_invoiced_minus_refunded_ex_tax' => __('Total Invoiced - Total Refunded (excl. Tax)'),
            'order_base_total_paid' => __('Total Paid'),
            'order_base_total_refunded' => __('Total Refunded'),
            'order_base_total_paid_minus_refunded' => __('Total Paid - Total Refunded'),
            'order_base_shipping_incl_tax' => __('Shipping Costs (incl. Tax)'),
            'order_base_shipping_amount' => __('Shipping Costs (excl. Tax)'),
            'order_total_qty_ordered' => __('Total Items'),
            'order_weight' => __('Total Weight'),
        ];

        $this->setAttributeOption($attributes);

        return $this;
    }

    /**
     * Validate Order Rule Condition
     *
     * @param DataObject $object
     * @return bool
     */
    public function validate(DataObject $object)
    {
        $period = $this->getPeriodRange();
        $totals = $this->getRule()->getOrderTotals($period['from'], $period['to']);

        $attributes = $this->getAttributeOption();
        foreach ($attributes as $k => $v) {
            $attributes[$k] = (float)$totals->getData($k);
        }

        $totals = new DataObject();
        $totals->setData($attributes);

        return parent::validate($totals);
    }
}
