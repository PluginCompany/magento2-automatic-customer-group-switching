<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Compatibility;

use Magento\Framework\DataObject;
use Magento\Framework\Model\AbstractModel;

class Combine extends \Magento\Rule\Model\Condition\Combine
{

    /**
     * @param DataObject $model
     * @return bool
     */
    public function validateDataObject(DataObject $model)
    {
        return $this->_isValid($model);
    }

    protected function _isValid($entity)
    {
        if (!$this->getConditions()) {
            return true;
        }

        $all = $this->getAggregator() === 'all';
        $true = (bool)$this->getValue();

        foreach ($this->getConditions() as $cond) {
            $validated = $cond->validate($entity);
            if ($all && $validated !== $true) {
                return false;
            } elseif (!$all && $validated === $true) {
                return true;
            }
        }
        return $all ? true : false;
    }
}
