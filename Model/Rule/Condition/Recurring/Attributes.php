<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */

/**
 * Class \PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Customer
 */
namespace PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Recurring;

use Magento\Framework\DataObject;
use PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Compatibility\AbstractCondition;

class Attributes extends AbstractCondition
{
    /**
     * Set attribute options
     *
     * @return $this
     */
    public function loadAttributeOptions()
    {
        $attributes = [
            'state' => __('Profile state'),
            'sku' => __('Product SKU'),
            'qty' => __('Product quantity'),
        ];

        $this->setAttributeOption($attributes);

        return $this;
    }


    /**
     * Get attribute element renderer
     *
     * @return mixed
     */
    public function getAttributeElement()
    {
        $element = parent::getAttributeElement();
        $element->setShowAsText(true);
        return $element;
    }

    /**
     * Get input type for comparison operator
     *
     * @return string
     */
    public function getInputType()
    {
        if ($this->getAttribute() == 'sku') {
            return 'string';
        }
        if ($this->getAttribute() == 'qty') {
            return 'numeric';
        }
        return 'select';
    }

    /**
     * Get value input renderer
     *
     * @return string
     */
    public function getValueElementType()
    {
        if (in_array($this->getAttribute(),['sku','qty'])) {
            return 'text';
        }
        return 'select';
    }

    /**
     * Get value select options for attributes
     *
     * @return mixed
     */
    public function getValueSelectOptions()
    {
        if (!$this->hasData('value_select_options')) {
            switch ($this->getAttribute()) {
                case 'state':
                     $options = [
                         ['value' => 'unknown','label' => 'Not Initialized'],
                         ['value' => 'pending','label' => 'Pending'],
                         ['value' => 'active','label' => 'Active'],
                         ['value' => 'suspended','label' => 'Suspended'],
                         ['value' => 'canceled','label' => 'Canceled'],
                         ['value' => 'expired','label' => 'Expired'],
                     ];
                    break;
                default:
                    $options = [];
                    break;
            }
            $this->setData('value_select_options', $options);
        }
        return $this->getData('value_select_options');
    }

    /**
     * Validate Address Rule Condition
     *
     * @param DataObject $model
     * @return bool
     */
    public function validate(DataObject $model)
    {
        return parent::validate($model);
    }
}
