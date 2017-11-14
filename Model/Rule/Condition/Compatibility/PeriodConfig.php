<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Model\Rule\Condition\Compatibility;

class PeriodConfig
{
    /**
     * The period options.
     * Complete history, between dates, last xx days, before xx days
     *
     * @return array
     */
    public function getPeriodOptions()
    {
        $options = [
            'all' => 'since day one',
            'between' => 'on and between dates',
            'lessdaysago' => 'of the last',
            'moredaysago' => 'older than',
        ];
        return $options;
    }

}