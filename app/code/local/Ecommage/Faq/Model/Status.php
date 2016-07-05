<?php

class Ecommage_Faq_Model_Status extends Varien_Object
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED => Mage::helper('faq')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('faq')->__('Disabled')
        );
    }

    public function getSelectOptionArray()
    {
        return array(
            array(
                'value' => Ecommage_Faq_Model_Status::STATUS_ENABLED,
                'label' => Mage::helper('faq')->__('Enabled'),
            ),

            array(
                'value' => Ecommage_Faq_Model_Status::STATUS_DISABLED,
                'label' => Mage::helper('faq')->__('Disabled'),
            ),
        );
    }
}