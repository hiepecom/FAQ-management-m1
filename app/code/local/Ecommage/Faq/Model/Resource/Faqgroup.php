<?php

class Ecommage_Faq_Model_Resource_Faqgroup extends Mage_Core_Model_Resource_Db_Abstract
{
    protected $_store = null;

    public function _construct()
    {
        $this->_init('faq/faqgroup', 'faq_group_id');
    }

}