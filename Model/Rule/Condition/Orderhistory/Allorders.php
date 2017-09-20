<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Orderhistory;

use Magento\Eav\Model\Entity\AttributeFactory;
use Magento\Framework\DataObject;
use PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Compatibility\AbstractCondition;
use Magento\Rule\Model\Condition\Context;
use Magento\Directory\Model\Config\Source\AllregionFactory;
use Magento\Directory\Model\Config\Source\CountryFactory;

class Allorders extends AbstractCondition
{
    /**
     * @var AllregionFactory
     */
    protected $_sourceAllregionFactory;

    /**
     * @var CountryFactory
     */
    protected $_sourceCountryFactory;

    /**
     * @var AttributeFactory
     */
    protected $_entityAttributeFactory;

    public function __construct(
        Context $context,
        AttributeFactory $entityAttributeFactory,
        AllregionFactory $sourceAllregionFactory,
        CountryFactory $sourceCountryFactory,
        array $data = [])
    {
        $this->_entityAttributeFactory = $entityAttributeFactory;
        $this->_sourceAllregionFactory = $sourceAllregionFactory;
        $this->_sourceCountryFactory = $sourceCountryFactory;

        parent::__construct($context, $data);
    }


    /**
     * Load attribute options
     *
     */
    public function loadAttributeOptions()
    {
        $attributes = [
            'base_subtotal_incl_tax' => __('Subtotal (incl. Tax)'),
            'base_subtotal' => __('Subtotal (excl. Tax)'),
            'base_grand_total' => __('Grand Total (incl. Tax)'),
            'base_shipping_incl_tax' => __('Shipping Costs (incl. Tax)'),
            'base_shipping_amount' => __('Shipping Costs (excl. Tax)'),
            'base_discount_amount' => __('Discount Amount'),
            'base_shipping_discount_amount' => __('Shipping Discount Amount'),
            'base_tax_amount' => __('Tax Amount'),
            'tax_percent' => __('Tax Percentage'),
            'base_total_invoiced' => __('Total Invoiced (incl. Tax)'), //al
            'base_total_invoiced_ex_tax' => __('Total Invoiced (excl. Tax)'), //all
            'base_total_invoiced_minus_refunded' => __('Total Invoiced - Total Refunded (incl. Tax)'),
            'base_total_invoiced_minus_refunded_ex_tax' => __('Total Invoiced - Total Refunded (excl. Tax)'),
            'base_total_paid' => __('Total Paid'),
            'base_total_refunded' => __('Total Refunded'),
            'base_total_paid_minus_refunded' => __('Total Paid - Total Refunded'),
            'total_qty_ordered' => __('Total Items'),
            'weight' => __('Total Weight'),
            'coupon_code' => __('Coupon Code'),
            'coupon_rule_name' => __('Coupon Rule Name'),
            'state' => __('Order State'),
            'status' => __('Order Status'),
//            'is_virtual' => __('Is Virtual'),
            'entity_id' => __('Order ID'),
            'increment_id' => __('Order Increment ID'),
            'customer_taxvat' => __('VAT Number'),
            'order_currency_code' => __('Currency Code'),
            'shipping_method' => __('Shipping Method'),
//            'created_at' => __('Order Date'),
        ];

        $bAttributes =
            [
                'billingattr_region_id' => __('Region (select)'),
                'billingattr_region' => __('Region (text)'),
                'billingattr_postcode' => __('Postal Code'),
                'billingattr_street' => __('Street'),
                'billingattr_city' => __('City'),
                'billingattr_country_id' => __('Country'),
                'billingattr_vat_id' => __('VAT Number'),
                'billingattr_vat_is_valid' => __('VAT Number Valid'),
            ];

        $sAttributes =
            [
                'shippingattr_region_id' => __('Region (select)'),
                'shippingattr_region' => __('Region (text)'),
                'shippingattr_postcode' => __('Postal Code'),
                'shippingattr_street' => __('Street'),
                'shippingattr_city' => __('City'),
                'shippingattr_country_id' => __('Country'),
                'shippingattr_vat_id' => __('VAT Number'),
                'shippingattr_vat_is_valid' => __('VAT Number Valid'),
            ];

        $attributes = array_merge($attributes, $bAttributes, $sAttributes);

        $this->setAttributeOption($attributes);

        return $this;
    }

    protected function _prepareValueOptions()
    {
        // Check that both keys exist. Maybe somehow only one was set not in this routine, but externally.
        $selectReady = $this->getData('value_select_options');
        $hashedReady = $this->getData('value_option');
        if ($selectReady && $hashedReady) {
            return $this;
        }

        // Get array of select options. It will be used as source for hashed options
        $selectOptions = null;
        if(is_object($this->getAttributeObject())) {
            $attributeObject = $this->getAttributeObject();
            if ($attributeObject->usesSource()) {
                if ($attributeObject->getFrontendInput() == 'multiselect') {
                    $addEmptyOption = false;
                } else {
                    $addEmptyOption = true;
                }
                $selectOptions = $attributeObject->getSource()->getAllOptions($addEmptyOption);
            }
        }

        // Set new values only if we really got them
        if ($selectOptions !== null) {
            // Overwrite only not already existing values
            if (!$selectReady) {
                $this->setData('value_select_options', $selectOptions);
            }
            if (!$hashedReady) {
                $hashedOptions = [];
                foreach ($selectOptions as $o) {
                    if (is_array($o['value'])) {
                        continue; // We cannot use array as index
                    }
                    $hashedOptions[$o['value']] = $o['label'];
                }
                $this->setData('value_option', $hashedOptions);
            }
        }

        return $this;
    }

    /**
     * Retrieve attribute object
     *
     * @return \Magento\Catalog\Model\ResourceModel\Eav\Attribute
     */
    public function getAttributeObject()
    {
        try {
            $attrCode = $this->getAttribute();
            if(stristr($attrCode,'billingattr_')){
                $attrCode = str_replace('billingattr_','',$attrCode);
                $entityCode = 'order_address';
            }elseif(stristr($attrCode,'shippingattr_')){
                $attrCode = str_replace('shippingattr_','',$attrCode);
                $entityCode = 'order_address';
            }else{
                $entityCode = 'order';
            }

            if(in_array($attrCode,['country_id','region_id'])){
                $entityCode = 'customer_address';
            }
            $obj = $this->_entityAttributeFactory->create()->loadByCode($entityCode,$attrCode);
        }
        catch (\Exception $e) {
            return 'string';
        }
        if($attrCode == 'region_id'){
            $obj->setFrontendInput('select');
        }
        return $obj;
    }

    /**
     * Retrieve value by option
     *
     * @param mixed $option
     * @return string
     */
    public function getValueOption($option=null)
    {
        $this->_prepareValueOptions();
        return $this->getData('value_option'.(!is_null($option) ? '/'.$option : ''));
    }

    /**
     * Get value select options for rule generation
     *
     * @return mixed
     */
    public function getValueSelectOptions()
    {
        if (!$this->hasData('value_select_options')) {
            switch ($this->getAttribute()) {
                case 'shippingattr_region_id':
                case 'billingattr_region_id':
                    $options = $this->_sourceAllregionFactory->create()
                        ->toOptionArray();
                    break;

                case 'billingattr_country_id':
                case 'shippingattr_country_id':
                    $options = $this->_sourceCountryFactory->create()
                        ->toOptionArray();
                    break;

                default:
                    $options = [];
            }
            $this->setData('value_select_options', $options);
        }
        return $this->getData('value_select_options');
    }

    /**
     * Retrieve attribute element
     *
     * @return \Magento\Framework\Form\Element\AbstractElement
     */
    public function getAttributeElement()
    {
        $element = parent::getAttributeElement();
        $element->setShowAsText(true);
        return $element;
    }

    /**
     * Retrieve input type
     *
     * @return string
     */
    public function getInputType()
    {
        if (!is_object($this->getAttributeObject())) {
            return 'string';
        }
        switch ($this->getAttributeObject()->getFrontendInput()) {
            case 'select':
                return 'select';

            case 'multiselect':
                return 'multiselect';

            case 'date':
                return 'date';

            case 'boolean':
                return 'boolean';

            default:
                return 'string';
        }
    }

    /**
     * @return string
     */
    public function getAttributeName()
    {
        $attribute = $this->getAttribute();
        $name = $this->getAttributeOption($attribute);

        $prefix = '';
        if(stristr($attribute, 'shippingattr')){
            $prefix = __('Shipping Address ');
        }
        if(stristr($attribute, 'billingattr')){
            $prefix = __('Billing Address ');
        }
        return $prefix . $name->getText();
    }

    /**
     * Retrieve value element type
     *
     * @return string
     */
    public function getValueElementType()
    {
        if ($this->getAttribute()==='attribute_set_id') {
            return 'select';
        }
        if (!is_object($this->getAttributeObject())) {
            return 'text';
        }
        switch ($this->getAttributeObject()->getFrontendInput()) {
            case 'select':
            case 'boolean':
                return 'select';

            case 'multiselect':
                return 'multiselect';

            case 'date':
                return 'date';

            default:
                return 'text';
        }
    }

    /**
     * Validate Orderhistory Rule Condition
     *
     * @param DataObject $object
     *
     * @return bool
     */
    public function validate(DataObject $object)
    {
        $valid = parent::validate($object);
        return $valid;
    }
}
