<?php

class Ecommage_Faq_Model_Resource_Faqgroup_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_previewFlag;

    public function _construct()
    {
        parent::_construct();
        $this->_init('faq/faqgroup');
    }

    public function addIsActiveFilter()
    {
        $this->addFilter('status', Ecommage_Faq_Model_Status::STATUS_ENABLED);
        return $this;
    }

}