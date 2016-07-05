<?php

class Ecommage_Faq_Block_Adminhtml_Faqgroup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('faq_group_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('faq')->__('FAQ Group Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => Mage::helper('faq')->__('FAQ Group Information'),
            'title' => Mage::helper('faq')->__('FAQ Group Information'),
            'content' => $this->getLayout()->createBlock('faq/adminhtml_faqgroup_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}