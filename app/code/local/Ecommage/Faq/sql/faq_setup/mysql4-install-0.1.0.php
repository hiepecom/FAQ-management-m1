<?php

/**
 * FAQ installation script
 */

/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;


/**
 * Delete tables if existed
 */
$installer->getConnection()->dropTable($installer->getTable('faq_relationship'));
$installer->getConnection()->dropTable($installer->getTable('faq_store'));
$installer->getConnection()->dropTable($installer->getTable('faq_group'));
$installer->getConnection()->dropTable($installer->getTable('faq'));

/**
 * Create FAQ item table
 */
$faqTable = $installer->getConnection()->newTable($installer->getTable('faq'))
    ->addColumn('faq_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'FAQ ID')
    ->addColumn('question', Varien_Db_Ddl_Table::TYPE_TEXT, Varien_Db_Ddl_Table::DEFAULT_TEXT_SIZE, array(
        'nullable' => false,
        'dafault' => ''
    ), 'Question')
    ->addColumn('answer', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
        'default' => '',
    ), 'Answer')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
        'nullable' => false,
        'default' => 0,
    ), 'Status')
    ->addColumn('created_time', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable' => true,
        'default' => null,
    ), 'Created Time')
    ->addColumn('updated_time', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable' => true,
        'default' => null,
    ), 'Updated Time')
    ->addColumn('total_rate', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => 0,
    ), 'Total Rate')
    ->addColumn('helpful_rate', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => 0,
    ), 'Helpful Rate')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => 0,
    ), 'Position')
    ->setComment('FAQ Item');


/**
 * Create FAQ group table
 */
$faqGroupTable = $installer->getConnection()->newTable($installer->getTable('faq_group'))
    ->addColumn('faq_group_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'FAQ group id')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
        'default' => '',
    ), 'Name')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
        'nullable' => false,
        'default' => 0,
    ), 'Status')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => 0,
    ), 'Position')
    ->setComment('FAQ group item');


/**
 * Create FAQ store table
 */
$faqStoreTable = $installer->getConnection()->newTable($installer->getTable('faq_store'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'ID')
    ->addColumn('faq_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
    ), 'FAQ ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
        'nullable' => false,
        'dafault' => ''
    ), 'Store ID')
    ->addForeignKey(
        $installer->getFkName($installer->getTable('faq_store'), 'faq_id', $installer->getTable('faq'), 'faq_id'),
        'faq_id',
        $installer->getTable('faq'),
        'faq_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )->addForeignKey(
        $installer->getFkName($installer->getTable('faq_store'), 'store_id', $installer->getTable('core/store'), 'store_id'),
        'store_id',
        $installer->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('FAQ store');


/**
 * Create FAQ relation ship table
 */
$faqRelationTable = $installer->getConnection()->newTable($installer->getTable('faq_relationship'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'ID')
    ->addColumn('faq_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
    ), 'FAQ ID')
    ->addColumn('faq_group_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
    ), 'FAQ group id')
    ->addForeignKey(
        $installer->getFkName($installer->getTable('faq_relationship'), 'faq_id', $installer->getTable('faq'), 'id'),
        'faq_id',
        $installer->getTable('faq'),
        'faq_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )->addForeignKey(
        $installer->getFkName($installer->getTable('faq_relationship'), 'faq_group_id', $installer->getTable('faq_group'), 'id'),
        'faq_group_id',
        $installer->getTable('faq_group'),
        'faq_group_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('FAQ Relationship');


/**
 * Run SQL script to create tables
 */
$installer->getConnection()->createTable($faqTable);
$installer->getConnection()->createTable($faqStoreTable);
$installer->getConnection()->createTable($faqGroupTable);
$installer->getConnection()->createTable($faqRelationTable);