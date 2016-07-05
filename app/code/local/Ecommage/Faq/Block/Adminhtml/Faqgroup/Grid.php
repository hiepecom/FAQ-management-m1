<?php

class Ecommage_Faq_Block_Adminhtml_Faqgroup_Grid extends Mage_Adminhtml_Block_Widget_Grid

{
    public function __construct()
    {
        parent::__construct();
        $this->setId('faqgroupGrid');
        $this->setDefaultSort('faq_group_id');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('faq/faqgroup')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('faq_group_id', array(
            'header' => Mage::helper('faq')->__('FAQ Group #'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'faq_group_id',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('faq')->__('Name'),
            'align' => 'left',
            'index' => 'name',
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
        $this->setMassactionIdField('faq_group_id');
        $this->getMassactionBlock()->setFormFieldName('faq_group_id');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('faq')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('catalog')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('enable', array(
            'label' => Mage::helper('faq')->__('Enable'),
            'url' => $this->getUrl('*/*/massUpdateStatus', array('status' => Ecommage_Faq_Model_Status::STATUS_ENABLED)),
        ));

        $this->getMassactionBlock()->addItem('disable', array(
            'label' => Mage::helper('faq')->__('Disable'),
            'url' => $this->getUrl('*/*/massUpdateStatus', array('status' => Ecommage_Faq_Model_Status::STATUS_DISABLED)),
        ));

        Mage::dispatchEvent('adminhtml_catalog_product_grid_prepare_massaction', array('block' => $this));
        return $this;
    }

}