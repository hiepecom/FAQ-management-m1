<?php

class Ecommage_Faq_Block_Faq extends Mage_Core_Block_Template
{
    public function _prepareLayout()
    {
        $this->getLayout()->getBlock('head')->setTitle('FAQ');
        return parent::_prepareLayout();
    }

    public function getFaqCollection($group)
    {
        $storeId = Mage::app()->getStore()->getId();
        $collection = Mage::getModel('faq/faq')->getCollection()
            ->addStoreFilter($storeId)
            ->addIsActiveFilter()
            ->setOrder('position', 'asc');

        $collection->join(
            array('faq_relationship'),
            'main_table.faq_id=faq_relationship.faq_id AND faq_relationship.faq_group_id=' . $group->getFaqGroupId(),
            array('faq_id' => 'faq_id', 'faq_group_id' => 'faq_group_id')
        );

        return $collection;
    }

    public function getFaqGroupCollection()
    {
        return Mage::getModel('faq/faqgroup')->getCollection()
            ->addIsActiveFilter()
            ->setOrder('position', 'asc');
    }

    public function getAnswer($faq)
    {
        $_cmsHelper = Mage::helper('cms');
        $_process = $_cmsHelper->getBlockTemplateProcessor();
        return $_process->filter($faq->getAnswer());
    }
}