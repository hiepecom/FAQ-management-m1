<?php
class Ecommage_Faq_Block_Adminhtml_Faqgroup extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_faqgroup';
        $this->_blockGroup = 'faq';
        $this->_headerText = Mage::helper('faq')->__('Manage FAQ Group');
        $this->_addButtonLabel = Mage::helper('faq')->__('Add FAQ Group');
        parent::__construct();
    }

    public function getHeaderCssClass()
    {
        return '';
    }
}