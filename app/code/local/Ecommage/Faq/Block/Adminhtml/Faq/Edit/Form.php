<?php

class Ecommage_Faq_Block_Adminhtml_Faq_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Define Form settings
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $headBlock = $this->getLayout()->getBlock('head');
            $headBlock->setCanLoadTinyMce(true);
            $headBlock->setCanLoadExtJs(true);

            $headBlock->addJs('mage/adminhtml/variables.js');
            $headBlock->addJs('mage/adminhtml/wysiwyg/widget.js');
            $headBlock->addJs('lib/flex.js');
            $headBlock->addJs('lib/FABridge.js');
            $headBlock->addJs('mage/adminhtml/flexuploader.js');
            $headBlock->addJs('mage/adminhtml/browser.js');
            $headBlock->addJs('extjs/ext-tree.js');
            $headBlock->addJs('extjs/ext-tree-checkbox.js');

            $headBlock->addItem('js_css', 'extjs/resources/css/ext-all.css');
            $headBlock->addItem('js_css', 'extjs/resources/css/ytheme-magento.css');
            $headBlock->addItem('js_css', 'prototype/windows/themes/default.css');
            $headBlock->addCss('lib/prototype/windows/themes/magento.css');
        }
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

}