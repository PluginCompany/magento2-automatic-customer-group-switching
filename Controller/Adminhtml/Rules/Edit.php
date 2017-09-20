<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Automatic Customer Group Switching for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CustomerGroupSwitching\Controller\Adminhtml\Rules;

use PluginCompany\CustomerGroupSwitching\Controller\Adminhtml\Rules;

class Edit extends Rules
{
    const ADMIN_RESOURCE = 'PluginCompany_CustomerGroupSwitching::rules';

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $this->initParams();

        if (!$this->doesRuleExist() && !$this->isNewRule()) {
            return $this->handleInvalidIdError();
        }

        $this
            ->addSessionDataToRuleModel()
            ->registerCurrentRule()
            ->initResultPage()
            ->addBreadCrumbsToResultPage()
            ->addTitleToResultPage()
        ;

        return $this->resultPage;
    }

    private function addBreadCrumbsToResultPage()
    {
        $this->resultPage
            ->addBreadcrumb(
                $this->getPageBreadCrumb(),
                $this->getPageBreadCrumb()
            );
        return $this;
    }

    private function getPageBreadCrumb()
    {
        if($this->doesRuleExist()){
            return __('Edit Automatic Group Switching Rule');
        }
        return __('New Automatic Customer Group Switching Rule');
    }

    private function addTitleToResultPage()
    {
        $this->prependToResultPageTitle(
            __('Automatic Group Switching Rules')
        );
        $this->addSubtitleToPageTitle();
        return $this;
    }

    private function addSubtitleToPageTitle()
    {
        if($this->doesRuleExist()) {
            $this->prependToResultPageTitle(
                __('Edit Automatic Customer Group Switching Rule ID: ') . $this->getRuleId()
            );
        }else{
            $this->prependToResultPageTitle(
                __('New Automatic Group Switching Rule')
            );
        }
        return $this;
    }

    private function getRuleId()
    {
        return $this->ruleModel->getId();
    }

    private function prependToResultPageTitle($text)
    {
        $this->resultPage
            ->getConfig()->getTitle()
            ->prepend(
                $text
            );
        return $this;
    }
}