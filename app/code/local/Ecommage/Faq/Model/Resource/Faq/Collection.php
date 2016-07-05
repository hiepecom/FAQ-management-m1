<?php

class Ecommage_Faq_Model_Resource_Faq_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_previewFlag;

    public function _construct()
    {
        parent::_construct();
        $this->_init('faq/faq');
        $this->_map['fields']['faq_id'] = 'main_table.faq_id';
        $this->_map['fields']['status'] = 'main_table.status';
        $this->_map['fields']['store'] = 'store_table.store_id';
        $this->_map['fields']['group'] = 'faq_relationship.faq_group_id';
    }

    public function setFirstStoreFlag($flag = false)
    {
        $this->_previewFlag = $flag;
        return $this;
    }

    /**
     * add attributes store and faq_group_id into FAQ item
     */
    protected function _afterLoad()
    {
        if ($this->_previewFlag) {
            $items = $this->getColumnValues('faq_id');
            if (count($items)) {
                $this->__afterLoadStoreId($items);
                $this->__afterLoadFaqGroupId($items);
            }
        }

        parent::_afterLoad();
    }

    /**
     * Add store attribute into FAQ item
     * @param $items
     */
    private function __afterLoadStoreId($items)
    {
        $faqStoreSelect = $this->getConnection()->select()
            ->from($this->getTable('faq_store'))
            ->where($this->getTable('faq_store') . '.faq_id IN (?)', $items);
        if ($result = $this->getConnection()->fetchPairs($faqStoreSelect)) {
            foreach ($this as $item) {
                if (!isset($result[$item->getData('faq_id')])) {
                    continue;
                }
                if ($result[$item->getData('faq_id')] == 0) {
                    $stores = Mage::app()->getStores(false, true);
                    $storeId = current($stores)->getId();
                    $storeCode = key($stores);
                } else {
                    $storeId = $result[$item->getData('faq_id')];
                    $storeCode = Mage::app()->getStore($storeId)->getCode();
                }
                $item->setData('_first_store_id', $storeId);
                $item->setData('store_code', $storeCode);
            }
        }
    }

    /**
     * Add attribute faq_group_id into FAQ item
     * @param $items
     */
    private function __afterLoadFaqGroupId($items)
    {
        $faqRelationshipSelect = $this->getConnection()->select()
            ->from($this->getTable('faq_relationship'))
            ->where($this->getTable('faq_relationship') . '.faq_id IN (?)', $items);
        if ($result = $this->getConnection()->fetchPairs($faqRelationshipSelect)) {
            foreach ($this as $item) {
                if (!isset($result[$item->getData('faq_id')])) {
                    continue;
                }

                $groupId = $result[$item->getData('faq_id')];

                $item->setData('faq_group_id', $groupId);
            }
        }
    }

    /**
     * Add filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @return Ecommage_Faq_Model_Resource_Faq_Collection
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array($store->getId());
        }
        $this->addFilter($this->_map['fields']['store'], array('in' => ($withAdmin ? array(0, $store) : $store)), 'public');
        return $this;
    }

    /**
     * Add filter by group id
     *
     * @param int|Ecommage_Faq_Model_Faqgroup $group
     * @return Ecommage_Faq_Model_Resource_Faq_Collection
     */
    public function addGroupFilter($group)
    {
        $this->addFilter($this->_map['fields']['group'], array('in' => $group));
        return $this;
    }

    public function addIsActiveFilter()
    {
        $this->addFilter($this->_map['fields']['status'], Ecommage_Faq_Model_Status::STATUS_ENABLED);
        return $this;
    }

    /**
     * Join store relation and faq_group tables if there is store or faq group filter
     */
    protected function _renderFiltersBefore()
    {
        $this->__renderFiltersBeforeStoreId();
        $this->__renderFiltersBeforeFaqGroupId();

        return parent::_renderFiltersBefore();
    }

    /**
     * Render filter if there is store filter
     */
    private function __renderFiltersBeforeStoreId()
    {
        if ($this->getFilter($this->_map['fields']['store'])) {
            $this->getSelect()->join(
                array('store_table' => $this->getTable('faq_store')),
                'main_table.faq_id = store_table.faq_id',
                array()
            )->group('main_table.faq_id');
        }
    }

    /**
     * Render filter if there is faq group filter
     */
    private function __renderFiltersBeforeFaqGroupId()
    {
        if ($this->getFilter($this->_map['fields']['group'])) {
            $this->getSelect()
                ->join(
                    array('faq_relationship' => $this->getTable('faq_relationship')),
                    'main_table.faq_id = faq_relationship.faq_id',
                    array('faq_id' => 'faq_id', 'faq_group_id' => 'faq_group_id')
                )
                ->join(
                    array('faq_group' => $this->getTable('faqgroup')),
                    'faq_relationship.faq_group_id = faq_group.faq_group_id',
                    array('faq_group_id' => 'faq_group_id', 'name' => 'name')
                )
                ->group('main_table.faq_id');
        }
    }

    /**
     * Get SQL for get record count.
     * Extra group by strip added.
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();

        $countSelect->reset(Zend_Db_Select::GROUP);

        return $countSelect;
    }
}