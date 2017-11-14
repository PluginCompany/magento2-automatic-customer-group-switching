<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */

namespace PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Compatibility;

abstract class AbstractProduct extends \Magento\Rule\Model\Condition\Product\AbstractProduct
{

    /**
     * Retrieve value element type
     *
     * @return string
     */
    public function getValueElementType()
    {
        if($this->getAttributeObject()->getFrontendInput() == 'datetime') {
            return 'date';
        }
        return parent::getValueElementType();
    }
}
