<?php

class Ecommage_Faq_Model_Resource_Faq extends Mage_Core_Model_Resource_Db_Abstract
{
    protected $_store = null;

    public function _construct()
    {
        // Note that the faq_id refers to the key field in your database table.
        $this->_init('faq/faq', 'faq_id');
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        $storeId = $object->getStoreId();
        if ($storeId) {
            $select->join(
                array('cps' => $this->getTable('faq_store')),
                $this->getMainTable() . '.faq_id = `cps`.faq_id'
            )
                ->where('`cps`.faq_id IN (' . Mage_Core_Model_App::ADMIN_STORE_ID . ', ?) ', $storeId)
                ->order('store_id DESC')
                ->limit(1);
        }

        return $select;
    }

    /*
      * @param Mage_Core_Model_Abstract $object
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {

        $object = $this->__afterLoadStoreId($object);
        $object = $this->__afterLoadFaqGroupId($object);

        return parent::_afterLoad($object);
    }

    private function __afterLoadStoreId($object)
    {
        $storeSelect = $this->_getReadAdapter()->select()
            ->from($this->getTable('faq_store'))
            ->where('faq_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($storeSelect)) {
            $storesArray = array();
            foreach ($data as $row) {
                $storesArray[] = $row['store_id'];
            }
            $object->setData('store_id', $storesArray);
        }

        return $object;
    }

    private function __afterLoadFaqGroupId($object)
    {
        $faqRelationshipSelect = $this->_getReadAdapter()->select()
            ->from($this->getTable('faq_relationship'))
            ->where('faq_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($faqRelationshipSelect)) {
            $faqGroupArray = array();
            foreach ($data as $row) {
                $faqGroupArray[] = $row['faq_group_id'];
            }
            $object->setData('faq_group_id', $faqGroupArray);
        }

        return $object;
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getId()) {
            $object->setCreatedTime(Mage::getSingleton('core/date')->gmtDate());
        }
        $object->setUpdatedTime(Mage::getSingleton('core/date')->gmtDate());
        return $this;
    }

    /**
     * Assign faq to store views and faq group
     *
     * @param Mage_Core_Model_Abstract $object
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $this->__afterSaveStoreId($object);
        $this->__afterSaveFaqGroupId($object);

        return parent::_afterSave($object);
    }

    private function __afterSaveStoreId($object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('faq_id = ?', $object->getId());
        $faqId = $object->getId();
        $writeAdapter = $this->_getWriteAdapter();

        // save FAQ store items
        $writeAdapter->delete($this->getTable('faq_store'), $condition);
        $stores = (array)$object->getData('stores');
        if (!$stores) {
            $stores = (array)$object->getData('store_id');
        }

        foreach ($stores as $store) {
            $storeArray = array();
            $storeArray['faq_id'] = $faqId;
            $storeArray['store_id'] = $store;
            $writeAdapter->insert($this->getTable('faq_store'), $storeArray);
        }
    }

    private function __afterSaveFaqGroupId($object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('faq_id = ?', $object->getId());
        $faqId = $object->getId();
        $writeAdapter = $this->_getWriteAdapter();

        // Save FAQ relation ship items
        $writeAdapter->delete($this->getTable('faq_relationship'), $condition);
        $faqGroupIds = (array)$object->getData('faq_group_id');
        foreach ($faqGroupIds as $faqGroupId) {
            $faqGroupArr = array();
            $faqGroupArr['faq_id'] = $faqId;
            $faqGroupArr['faq_group_id'] = $faqGroupId;
            $writeAdapter->insert($this->getTable('faq_relationship'), $faqGroupArr);
        }
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        return $this->_getReadAdapter()->fetchCol($this->_getReadAdapter()->select()
            ->from($this->getTable('faq_store'), 'store_id')
            ->where("{$this->getIdFieldName()} = ?", $id)
        );
    }

    /**
     * Set store model
     *
     * @param Mage_Core_Model_Store $store
     * @return Mage_Cms_Model_Resource_Page
     */
    public function setStore($store)
    {
        $this->_store = $store;
        return $this;
    }

    /**
     * Retrieve store model
     *
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        return Mage::app()->getStore($this->_store);
    }

}