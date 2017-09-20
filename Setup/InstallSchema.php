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
        $installer = $setup;
        $installer->startSetup();

        $tableGroup = $installer->getTable('layout_group');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableGroup) != true) {
            // Create table
            $table = $installer->getConnection()
                ->newTable($tableGroup)
                ->addColumn(
                    'group_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Id'
                )
                ->addColumn(
                    'group_code',
                    Table::TYPE_VARCHAR,
                    255,
                    ['nullable' => false, 'default' => null],
                    'Group Code'
                )
                ->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true, 'default' => null],
                    'Title'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => true, 'default' => null],
                    'Description'
                )
                ->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Sort Order'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => true, 'default' => Table::TIMESTAMP_UPDATE],
                    'Updated At'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => true],
                    'Created At'
                )
                ->addIndex(
                    $installer->getIdxName(
                        'layout_group',
                        ['group_code'],
                        AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['group_code'],
                    ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                ->setComment('Layout Group Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }

        $tableElement = $installer->getTable('layout_element');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableElement) != true) {
            // Create table
            $table = $installer->getConnection()
                ->newTable($tableElement)
                ->addColumn(
                    'element_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary'  => true
                    ],
                    'Id'
                )
                ->addColumn(
                    'group_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false
                    ],
                    'Fk Group'
                )
                ->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Title'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => true],
                    'Description'
                )
                ->addColumn(
                    'link',
                    Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => true],
                    'Link'
                )
                ->addColumn(
                    'link_text',
                    Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => true],
                    'Link Text'
                )
                ->addColumn(
                    'image',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Image'
                )
                ->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Sort Order'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => true, 'default' => Table::TIMESTAMP_UPDATE],
                    'Updated At'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => true ],
                    'Created At'
                )
                ->addForeignKey(
                    $installer->getFkName('layout_element', 'group_id', 'layout_group', 'group_id'),
                    'group_id',
                    $installer->getTable('layout_group'),
                    'group_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Element Group Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
        
        
         $tableTemplate = $installer->getTable('layout_group_template');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableTemplate) != true) {
            // Create table
            $table = $installer->getConnection()
                ->newTable($tableTemplate)
                ->addColumn(
                    'template_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Id'
                )
                ->addColumn(
                    'group_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false
                    ],
                    'Fk Group'
                )
                ->addColumn(
                    'label',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true, 'default' => null],
                    'Label'
                )
                ->addColumn(
                    'type',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true, 'default' => null],
                    'Type'
                )
                ->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Sort Order'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => true, 'default' => Table::TIMESTAMP_UPDATE],
                    'Updated At'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => true],
                    'Created At'
                )
                ->addForeignKey(
                    $installer->getFkName('layout_group_template', 'group_id', 'layout_group', 'group_id'),
                    'group_id',
                    $installer->getTable('layout_group'),
                    'group_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Layout Group Template Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
        
        $tableTemplateElement = $installer->getTable('layout_group_template_element');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableTemplateElement) != true) {
            // Create table
            $table = $installer->getConnection()
                ->newTable($tableTemplateElement)
                ->addColumn(
                    'template_element_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Id'
                )
                ->addColumn(
                    'element_id',
                    Table::TYPE_INTEGER,
                    255,
                    ['nullable' => false],
                    'Template Id'
                )
                ->addColumn(
                    'group_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false
                    ],
                    'Fk Group'
                )
                ->addColumn(
                    'template_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false
                    ],
                    'Fk Template'
                )
                ->addColumn(
                    'content',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => null],
                    'Content'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => true, 'default' => Table::TIMESTAMP_UPDATE],
                    'Updated At'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => true],
                    'Created At'
                )
                ->addForeignKey(
                    $installer->getFkName('layout_group_template_element', 'group_id', 'layout_group', 'group_id'),
                    'group_id',
                    $installer->getTable('layout_group'),
                    'group_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName('layout_group_template_element', 'template_id', 'layout_group_template', 'template_id'),
                    'template_id',
                    $installer->getTable('layout_group_template'),
                    'template_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Layout Group Template Element Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
