<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */


namespace PluginCompany\CustomerGroupSwitching\Api\Data;

interface RuleSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Rule list.
     * @return \PluginCompany\CustomerGroupSwitching\Api\Data\RuleInterface[]
     */
    
    public function getItems();

    /**
     * Set rule_id list.
     * @param \PluginCompany\CustomerGroupSwitching\Api\Data\RuleInterface[] $items
     * @return $this
     */
    
    public function setItems(array $items);
}
