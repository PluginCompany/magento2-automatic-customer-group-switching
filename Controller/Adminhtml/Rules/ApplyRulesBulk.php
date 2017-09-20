<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */

namespace PluginCompany\CustomerGroupSwitching\Controller\Adminhtml\Rules;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use PluginCompany\CustomerGroupSwitching\Model\RuleFactory;

class ApplyRulesBulk extends Action
{
    const ADMIN_RESOURCE = 'PluginCompany_CustomerGroupSwitching::rules';
    /**
     * @var Registry
     */
    private $frameworkRegistry;

    /**
     * @var RuleFactory
     */
    private $ruleFactory;

    public function __construct(
        Context $context,
        Registry $frameworkRegistry,
        RuleFactory $modelRuleFactory
    )
    {

        $this->frameworkRegistry = $frameworkRegistry;
        $this->ruleFactory = $modelRuleFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        $this->registerBulkSwitch();

        $this
            ->getResponse()
            ->setHeader('Content-type','application/json',true)
            ->setBody(
                json_encode(
                    $this->executeRulesAndGetResult()
                )
            )
        ;
    }

    private function registerBulkSwitch()
    {
        $this->frameworkRegistry
            ->register('is_groupswitch_bulk',1)
        ;
        return $this;
    }

    private function executeRulesAndGetResult()
    {
        $result = $this->applyRules();
        if(!count($result)){
            $result = 'done';
        }
        return $result;
    }

    private function applyRules()
    {
        return $this->ruleFactory->create()
            ->applySelectedRulesForCustomerRange(
                $this->getOffset(),
                $this->getLimit(),
                $this->getRuleIds()
            );
    }

    private function getRuleIds()
    {
        return $this->getRequest()
            ->getParam('ruleIds')
            ;
    }

    private function getOffset()
    {
        return $this->getRequest()->getParam('offset');
    }

    private function getLimit()
    {
        return 10;
    }
}
