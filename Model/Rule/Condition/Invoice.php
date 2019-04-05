<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Class \PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Invoice
 */
namespace PluginCompany\CustomerGroupSwitching\Model\Rule\Condition;

use Magento\Framework\DataObject;
use Magento\Framework\Model\AbstractModel;

class Invoice extends Totals
{
    protected $_typeName = 'invoice';

    /**
     * Prepare attribute options for rule
     *
     * @return $this
     */
    public function loadAttributeOptions()
    {
        $attributes = [
            'invoice_base_subtotal_incl_tax' => __('Subtotal (incl. Tax)'),
            'invoice_base_subtotal' => __('Subtotal (excl. Tax)'),
            'invoice_base_grand_total' => __('Grand Total (incl. Tax)'),
            'invoice_base_shipping_incl_tax' => __('Shipping Costs (incl. Tax)'),
            'invoice_base_shipping_amount' => __('Shipping Costs (excl. Tax)'),
            'invoice_total_qty' => __('Total Items'),
        ];

        $this->setAttributeOption($attributes);

        return $this;
    }

    /**
     * Validate Invoice Rule Condition
     *
     * @param DataObject $object
     * @return bool
     */
    public function validateDataObject(DataObject $object)
    {
        $period = $this->getPeriodRange();
        $totals = $this->getRule()->getInvoiceTotals($period['from'], $period['to']);

        $attributes = $this->getAttributeOption();
        foreach ($attributes as $k => $v) {
            $attributes[$k] = (float)$totals->getData($k);
        }

        $totals = new DataObject();
        $totals->setData($attributes);

        return parent::validateDataObject($totals);
    }

}
