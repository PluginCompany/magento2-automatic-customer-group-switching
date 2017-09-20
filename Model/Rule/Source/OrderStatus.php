<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Model\Rule\Source;;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory;

/**
 * Class OrderStatus
 */
class OrderStatus implements OptionSourceInterface
{
    private $orderStatusCollectionFactory;

    /**
     * OrderStatus constructor.
     * @param CollectionFactory $statusCollectionFactory
     */
    public function __construct
    (
        CollectionFactory $statusCollectionFactory
    )
    {
        $this->orderStatusCollectionFactory = $statusCollectionFactory;
        return $this;
    }
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->orderStatusCollectionFactory
            ->create()
            ->toOptionArray();
    }
}