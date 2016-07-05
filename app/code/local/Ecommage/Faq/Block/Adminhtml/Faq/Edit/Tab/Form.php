<?php

class Ecommage_Faq_Block_Adminhtml_Faq_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
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
        $model = Mage::registry('faq_data');

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('faq_form', array(
            'legend' => Mage::helper('faq')->__('FAQ information'),
            'class' => 'fieldset-wide'));

        $fieldset->addField('question', 'text', array(
            'label' => Mage::helper('faq')->__('Question'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'question',
        ));


        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect',
                array(
                    'name' => 'stores[]',
                    'label' => Mage::helper('faq')->__('Store view'),
                    'title' => Mage::helper('faq')->__('Store view'),
                    'required' => true,
                    'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true)));
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('faq_group_id', 'multiselect',
            array(
                'name' => 'faq_group_id[]',
                'label' => Mage::helper('faq')->__('FAQ Group'),
                'title' => Mage::helper('faq')->__('FAQ Group'),
                'required' => true,
                'values' => Mage::helper('faq')->getGroups()));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('faq')->__('Status'),
            'name' => 'status',
            'values' => Mage::getModel('faq/status')->getSelectOptionArray()
        ));

        $fieldset->addField('answer', 'editor', array(
            'name' => 'answer',
            'label' => Mage::helper('faq')->__('Answer'),
            'title' => Mage::helper('faq')->__('Answer'),
            'config' => Mage::helper('faq')->setWysiwygConfig(),
            'wysiwyg' => true,
            'required' => true,
        ));

        $fieldset->addField('position', 'text', array(
            'label' => Mage::helper('faq')->__('Position'),
            'name' => 'position',
            'class' => 'validate-number',
            'note' => 'Position of this question in FAQ page'
        ));

        if (Mage::getSingleton('adminhtml/session')->getFaqData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFaqData());
            Mage::getSingleton('adminhtml/session')->setFaqData(null);
        } elseif (Mage::registry('faq_data')) {
            $form->setValues(Mage::registry('faq_data')->getData());
        }
        return parent::_prepareForm();
    }
}