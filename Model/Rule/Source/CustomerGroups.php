<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Model\Rule\Source;

use Magento\Customer\Model\Customer\Source\Group;

class CustomerGroups extends Group
{
    /**
     * Return array of customer groups
     *
     * @return array
     */
    public function toOptionArray()
    {
        $groups = parent::toOptionArray();
        foreach($groups as $key => $group)
        {
            if($group['value'] == 0)
            {
                unset($groups[$key]);
            }
        }

        return $groups;
    }

}

