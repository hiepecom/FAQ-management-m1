<?php

class Ecommage_Faq_Block_Adminhtml_Faqgroup_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'faq';
        $this->_controller = 'adminhtml_faqgroup';

        $this->_updateButton('save', 'label', Mage::helper('faq')->__('Save FAQ Group'));
        $this->_updateButton('delete', 'label', Mage::helper('faq')->__('Delete FAQ Group'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('faqgroup_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'faqgroup_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'faqgroup_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('faqgroup_data') && Mage::registry('faqgroup_data')->getId()) {
            return Mage::helper('faq')->__("Edit Group");
        } else {
            return Mage::helper('faq')->__('Add Group');
        }
    }

    public function getHeaderCssClass()
    {
        return '';
    }
}