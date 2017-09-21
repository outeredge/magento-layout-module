<?php

namespace OuterEdge\Layout\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        
        $elementEntity = \OuterEdge\Layout\Model\Element::ENTITY;
        
        $table = $setup->getConnection()->newTable(
            $setup->getTable('layout_group')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'group_code',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => null],
            'Group Code'
        )->addColumn(
            'title',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true, 'default' => null],
            'Title'
        )->addColumn(
            'description',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => true, 'default' => null],
            'Description'
        )->addColumn(
            'sort_order',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '0'],
            'Sort Order'
        )->setComment(
            'Layout Group Table'
        );
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable($elementEntity . '_entity'))
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity ID'
            )
            ->addColumn(
                'group_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Group FK'
            )
            ->addColumn(
                'title',
                Table::TYPE_TEXT,
                64,
                [],
                'Title'
            )
            ->addColumn(
                'description',
                 Table::TYPE_TEXT,
                '2M',
                [],
                'Description'
            )
            ->addColumn(
                'sort_order',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Sort Order'
            )
            ->addForeignKey(
                $setup->getFkName($elementEntity . '_entity', 'group_id', 'layout_group', 'entity_id'),
                'group_id',
                $setup->getTable('layout_group'),
                'entity_id',
                Table::ACTION_CASCADE
            )->setComment('Layout Element Table');
        $setup->getConnection()->createTable($table);
        
        $table = $setup->getConnection()
            ->newTable($setup->getTable($elementEntity . '_entity_int'))
            ->addColumn(
                'value_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                Table::TYPE_INTEGER,
                null,
                [],
                'Value'
            )
            ->addIndex(
                $setup->getIdxName(
                    $elementEntity . '_entity_int',
                    ['entity_id', 'attribute_id', 'store_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName($elementEntity . '_entity_int', ['attribute_id']),
                ['attribute_id']
            )
            ->addIndex(
                $setup->getIdxName($elementEntity . '_entity_int', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName($elementEntity . '_entity_int', 'attribute_id', 'eav_attribute', 'attribute_id'),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName($elementEntity . '_entity_int', 'entity_id', $elementEntity . '_entity', 'entity_id'),
                'entity_id',
                $setup->getTable($elementEntity . '_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName($elementEntity . '_entity_int', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Layout Element Integer Attribute Backend Table');
        $setup->getConnection()->createTable($table);
        $table = $setup->getConnection()
            ->newTable($setup->getTable($elementEntity . '_entity_datetime'))
            ->addColumn(
                'value_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                Table::TYPE_DATETIME,
                null,
                [],
                'Value'
            )
            ->addIndex(
                $setup->getIdxName(
                    $elementEntity . '_entity_datetime',
                    ['entity_id', 'attribute_id', 'store_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName($elementEntity . '_entity_datetime', ['attribute_id']),
                ['attribute_id']
            )
            ->addIndex(
                $setup->getIdxName($elementEntity . '_entity_datetime', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    $elementEntity . '_entity_datetime',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $elementEntity . '_entity_datetime',
                    'entity_id',
                    $elementEntity . '_entity',
                    'entity_id'
                ),
                'entity_id',
                $setup->getTable($elementEntity . '_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName($elementEntity . '_entity_datetime', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Layout Element Datetime Attribute Backend Table');
        $setup->getConnection()->createTable($table);
        $table = $setup->getConnection()
            ->newTable($setup->getTable($elementEntity . '_entity_decimal'))
            ->addColumn(
                'value_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                Table::TYPE_DECIMAL,
                '12,4',
                [],
                'Value'
            )
            ->addIndex(
                $setup->getIdxName(
                    $elementEntity . '_entity_decimal',
                    ['entity_id', 'attribute_id', 'store_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName($elementEntity . '_entity_decimal', ['store_id']),
                ['store_id']
            )
            ->addIndex(
                $setup->getIdxName($elementEntity . '_entity_decimal', ['attribute_id']),
                ['attribute_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    $elementEntity . '_entity_decimal',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $elementEntity . '_entity_decimal',
                    'entity_id',
                    $elementEntity . '_entity',
                    'entity_id'
                ),
                'entity_id',
                $setup->getTable($elementEntity . '_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName($elementEntity . '_entity_decimal', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Layout Element Decimal Attribute Backend Table');
        $setup->getConnection()->createTable($table);
        $table = $setup->getConnection()
            ->newTable($setup->getTable($elementEntity . '_entity_varchar'))
            ->addColumn(
                'value_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                Table::TYPE_TEXT,
                255,
                [],
                'Value'
            )
            ->addIndex(
                $setup->getIdxName(
                    $elementEntity . '_entity_varchar',
                    ['entity_id', 'attribute_id', 'store_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName($elementEntity . '_entity_varchar', ['attribute_id']),
                ['attribute_id']
            )
            ->addIndex(
                $setup->getIdxName($elementEntity . '_entity_varchar', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    $elementEntity . '_entity_varchar',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $elementEntity . '_entity_varchar',
                    'entity_id',
                    $elementEntity . '_entity',
                    'entity_id'
                ),
                'entity_id',
                $setup->getTable($elementEntity . '_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName($elementEntity . '_entity_varchar', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Layout Element Varchar Attribute Backend Table');
        $setup->getConnection()->createTable($table);
        $table = $setup->getConnection()
            ->newTable($setup->getTable($elementEntity . '_entity_text'))
            ->addColumn(
                'value_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                Table::TYPE_TEXT,
                '64k',
                [],
                'Value'
            )
            ->addIndex(
                $setup->getIdxName(
                    $elementEntity . '_entity_text',
                    ['entity_id', 'attribute_id', 'store_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName($elementEntity . '_entity_text', ['attribute_id']),
                ['attribute_id']
            )
            ->addIndex(
                $setup->getIdxName($elementEntity . '_entity_text', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    $elementEntity . '_entity_text',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $elementEntity . '_entity_text',
                    'entity_id',
                    $elementEntity . '_entity',
                    'entity_id'
                ),
                'entity_id',
                $setup->getTable($elementEntity . '_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName($elementEntity . '_entity_text', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Layout Element Text Attribute Backend Table');
        $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }
}
