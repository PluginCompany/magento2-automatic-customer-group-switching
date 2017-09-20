<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */


namespace PluginCompany\CustomerGroupSwitching\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Groupswitch extends Container
{

	public function __construct()
	{
        $this->_controller = "adminhtml_groupswitch";
        $this->_blockGroup = "groupswitch";
        $this->_headerText = __("Automatic Customer Group Switching Rules");
        $this->_addButtonLabel = __("Add New Rule");
        parent::__construct();
	}

}