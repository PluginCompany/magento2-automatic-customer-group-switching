<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */

/**
 * Class \PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Product\Neworder\Combine
 */
namespace PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Recurring;

use PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Compatibility\Combine as ConditionCombine;
use Magento\Rule\Model\Condition\Context;

class Combine extends ConditionCombine
{
    /**
     * @var AttributesFactory
     */
    protected $_recurringAttributesFactory;

    public function __construct(Context $context, 
        AttributesFactory $recurringAttributesFactory, 
        array $data = [])
    {
        $this->_recurringAttributesFactory = $recurringAttributesFactory;

        parent::__construct($context, $data);
        $this->setType('groupswitch/rule_condition_recurring_combine');
    }

    /**
     * Get select options for condtion combination
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        //get cart conditions
        $condition = $this->_recurringAttributesFactory->create();
        $recurringAttributes = $condition->loadAttributeOptions()->getAttributeOption();
        $attributes = [];
        foreach ($recurringAttributes as $code=>$label) {
            $attributes[] = ['value'=>'groupswitch/rule_condition_recurring_attributes|'.$code, 'label'=>$label];
        }

        //set condtions
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive($conditions, [
            ['label'=>__('Attributes'), 'value'=>$attributes]
        ]);
        return $conditions;
    }

}
