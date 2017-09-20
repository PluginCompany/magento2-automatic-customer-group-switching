<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Recurring;

use Magento\Framework\DataObject;

class Found
    extends Combine
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setType('groupswitch/rule_condition_recurring_found');
    }

    /**
     * Load value options
     *
     * @return \Magento\SalesRule\Model\Rule\Condition\Product\Found
     */
    public function loadValueOptions()
    {
        $this->setValueOption([
            1 => __('FOUND'),
            0 => __('NOT FOUND')
        ]);
        return $this;
    }

    /**
     * HTML for rule creation
     *
     * @return string
     */
    public function asHtml()
    {
        $html = $this->getTypeElement()->getHtml() . __("If a recurring profile is %1 with %2 of these conditions true:", $this->getValueElement()->getHtml(), $this->getAggregatorElement()->getHtml());
        if ($this->getId() != '1') {
            $html.= $this->getRemoveLinkHtml();
        }
        return $html;
    }

    /**
     * Validate recurring profile conditions
     *
     * @param $object 
     * @return bool
     */
    public function validate(DataObject $object)
    {

        $all = $this->getAggregator()==='all';
        $true = (bool)$this->getValue();
        $found = false;

        if ($object->getRecurringProfileData()) {
            foreach ($object->getRecurringProfileData()->getItems() as $item) {
                $found = $all;
                foreach ($this->getConditions() as $cond) {
                    $validated = $cond->validate($item);
                    if (($all && !$validated) || (!$all && $validated)) {
                        $found = $validated;
                        break;
                    }
                }
                if (($found && $true) || (!$true && $found)) {
                    break;
                }
            }
        }
        // found an item and we're looking for existing one
        if ($found && $true) {
            return true;
        }
        // not found and we're making sure it doesn't exist
        elseif (!$found && !$true) {
            return true;
        }
        return false;
    }
}
