<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Abstract Rule condition data model
 *
 * @method string getOperator()
 * @method string getFormName()
 * @method setFormName()
 */
namespace PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Compatibility;

use Magento\Framework\DataObject;
use Magento\Rule\Model\Condition\ConditionInterface;

abstract class AbstractCondition
    extends \Magento\Rule\Model\Condition\AbstractCondition
    implements ConditionInterface
{

    public function validateDataObject(DataObject $dataObject)
    {
        $attributeValue = $dataObject->getData($this->getAttribute());
        return $this->validateAttribute($attributeValue);
    }

}
