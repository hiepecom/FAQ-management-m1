<?php

class Ecommage_Faq_Model_Faqgroup extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('faq/faqgroup');
    }
}