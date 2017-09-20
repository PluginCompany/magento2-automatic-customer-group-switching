<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Controller\Adminhtml\Rules;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;

class Create extends Action
{
    const ADMIN_RESOURCE = 'PluginCompany_CustomerGroupSwitching::rules';

   /**
    * @var \Magento\Backend\Model\View\Result\Forward
    */
   protected $resultForwardFactory;

   /**
    * @param Context $context
    * @param ForwardFactory $resultForwardFactory
    */
   public function __construct(
       Context $context,
       ForwardFactory $resultForwardFactory
   ) {
       $this->resultForwardFactory = $resultForwardFactory;
       parent::__construct($context);
   }

   /**
    * Forward to edit
    *
    * @return \Magento\Backend\Model\View\Result\Forward
    */
   public function execute()
   {
       return $this->resultForwardFactory->create()
           ->forward('edit')
           ;
   }
}