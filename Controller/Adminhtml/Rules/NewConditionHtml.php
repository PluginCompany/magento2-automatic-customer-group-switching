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
use Magento\Rule\Model\Condition\AbstractCondition;
use Psr\Log\LoggerInterface;

class NewConditionHtml extends Action
{
    const ADMIN_RESOURCE = 'PluginCompany_CustomerGroupSwitching::rules';

    private $id;
    private $formName;
    private $typeArr;
    private $type;
    private $model;
    private $form;

    private $logger;

    /**
     * NewConditionHtml constructor.
     * @param LoggerInterface $logger
     * @param Context $context
     */
    public function __construct(
        LoggerInterface $logger,
        Context $context
    ) {
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * New condition html action
     *
     * @return void
     */
    public function execute()
    {
        try {
            $this->runExecute();
        }catch(\Exception $e) {
            $this->processError($e);
        }catch(\Throwable $e) {
            $this->processError($e);
        }
    }

    private function runExecute()
    {
        $this
            ->initParams()
            ->initModel();

        $this->getResponse()
            ->setBody(
                $this->getHtmlFromModel()
            );
    }

    private function initParams()
    {
        $this->id = $this->getRequest()->getParam('id');
        $this->formName = $this->getRequest()->getParam('form_namespace');
        $this->typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $this->type = $this->typeArr[0];
        $this->form = $this->getRequest()->getParam('form');
        return $this;
    }

    private function initModel()
    {
        $this->model = $this->_objectManager->create(
            $this->type
        )->setId(
            $this->id
        )->setType(
            $this->type
        )->setRule(
            $this->_objectManager->create('PluginCompany\CustomerGroupSwitching\Model\Rule')
        )->setPrefix(
            'conditions'
        );

        if (!empty($this->typeArr[1])) {
            $this->model->setAttribute($this->typeArr[1]);
        }
        return $this;
    }

    private function getHtmlFromModel()
    {
        if ($this->model instanceof AbstractCondition) {
            $this->model->setJsFormObject(
                $this->form
            );
            $this->model->setFormName(
                $this->formName
            );
            return $this->model->asHtmlRecursive();
        }
        return '';
    }

    private function processError($e)
    {
        $this->logger
            ->critical($e->getMessage());

        $this->getResponse()
            ->setBody(__('An error occured, please contact support'))
        ;
    }
}
