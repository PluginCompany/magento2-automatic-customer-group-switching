<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */

namespace PluginCompany\CustomerGroupSwitching\Block\Adminhtml\Rules\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveAndExecuteButton
    extends GenericButton
    implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        return
            [
                'label' => __('Save and Execute'),
                'class' => 'save',
                'on_click' => '',
                'sort_order' => 80,
                'data_attribute' => [
                    'mage-init' => [
                        'Magento_Ui/js/form/button-adapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'groupswitch_rules_form.groupswitch_rules_form',
                                    'actionName' => 'save',
                                    'params' => [
                                        true,
                                        ['execute' => 1],
                                    ]
                                ]
                            ]
                        ]
                    ],

                ]
            ];
    }

    /**
     * Get URL for delete button
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl(
            '*/*/delete',
            [
                'id' => $this->getModelId()
            ]
        );
    }
}

