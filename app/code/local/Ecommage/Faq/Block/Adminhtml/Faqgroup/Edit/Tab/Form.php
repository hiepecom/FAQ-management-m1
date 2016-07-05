<?php

class Ecommage_Faq_Block_Adminhtml_Faqgroup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Define Form settings
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('faqgroup_data');

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('faqgroup_form', array(
            'legend' => Mage::helper('faq')->__('FAQ Group information'),
            'class' => 'fieldset-wide'));

        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('faq')->__('Group Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name',
        ));

        $fieldset->addField('position', 'text', array(
            'label' => Mage::helper('faq')->__('Position'),
            'name' => 'position',
            'class' => 'validate-number',
            'note' => 'Position of this question group in FAQ page'
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('faq')->__('Status'),
            'name' => 'status',
            'values' => Mage::getModel('faq/status')->getSelectOptionArray()
        ));

        if (Mage::getSingleton('adminhtml/session')->getFaqGroupData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFaqGroupData());
            Mage::getSingleton('adminhtml/session')->setFaqGroupData(null);
        } elseif (Mage::registry('faqgroup_data')) {
            $form->setValues(Mage::registry('faqgroup_data')->getData());
        }
        return parent::_prepareForm();
    }
}