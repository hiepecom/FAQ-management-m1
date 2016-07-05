<?php

class Ecommage_Faq_Block_Adminhtml_Faq_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('faqGrid');
        $this->setDefaultSort('faq_id');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('faq/faq')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('faq_id', array(
            'header' => Mage::helper('faq')->__('FAQ #'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'faq_id',
        ));

        $this->addColumn('faq_group_id', array(
            'header' => Mage::helper('faq')->__('Group'),
            'align' => 'left',
            'index' => 'faq_group_id',
            'renderer' => 'Ecommage_Faq_Block_Widget_Grid_Colum_Renderer_Group',
            'type' => 'options',
            'options' => Mage::helper('faq')->getGroupFiltersGrid(),
            'filter_condition_callback' => array($this, '_filterGroupCondition'),
        ));

        $this->addColumn('question', array(
            'header' => Mage::helper('faq')->__('Question'),
            'align' => 'left',
            'index' => 'question',
        ));

        $this->addColumn('store_id',
            array(
                'header' => Mage::helper('faq')->__('Store view'),
                'index' => 'store_id',
                'type' => 'store',
                'store_all' => true,
                'store_view' => true,
                'sortable' => false,
                'filter_condition_callback' => array($this, '_filterStoreCondition')
            ));

        $this->addColumn('status', array(
            'header' => Mage::helper('faq')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getModel('faq/status')->getOptionArray()
        ));

        $this->addColumn('position', array(
            'header' => Mage::helper('faq')->__('Position'),
            'index' => 'position',
            'type' => 'number',
        ));

        if (Mage::helper('faq')->isHelpfulEnabled()) {
            $this->addColumn('total_rate', array(
                'header' => Mage::helper('faq')->__('Total Rate'),
                'index' => 'total_rate',
                'type' => 'number',
            ));

            $this->addColumn('helpful_rate', array(
                'header' => Mage::helper('faq')->__('Helpful Rate'),
                'index' => 'helpful_rate',
                'type' => 'number',
            ));
        }

        $this->addColumn('created_time', array(
            'header' => Mage::helper('faq')->__('Date Created'),
            'index' => 'created_time',
            'width' => '150px',
            'type' => 'datetime',
        ));

        $this->addColumn('updated_time', array(
            'header' => Mage::helper('faq')->__('Last Modified'),
            'index' => 'updated_time',
            'width' => '150px',
            'type' => 'datetime',
        ));

        $this->addColumn('action',
            array(
                'header' => Mage::helper('faq')->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('faq')->__('Edit'),
                        'url' => array('base' => '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));


        return parent::_prepareColumns();
    }


    protected function _afterToHtml($html)
    {
        return parent::_afterToHtml($html);
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('faq_id');
        $this->getMassactionBlock()->setFormFieldName('faq');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('faq')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('catalog')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('enable', array(
            'label' => Mage::helper('faq')->__('Enable'),
            'url' => $this->getUrl('*/*/massUpdateStatus', array('status' => Ecommage_Faq_Model_Status::STATUS_ENABLED))
        ));

        $this->getMassactionBlock()->addItem('disable', array(
            'label' => Mage::helper('faq')->__('Disable'),
            'url' => $this->getUrl('*/*/massUpdateStatus', array('status' => Ecommage_Faq_Model_Status::STATUS_DISABLED))
        ));

        Mage::dispatchEvent('adminhtml_catalog_product_grid_prepare_massaction', array('block' => $this));
        return $this;
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    protected function _filterGroupCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addGroupFilter($value);
    }

}