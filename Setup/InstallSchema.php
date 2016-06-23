<?php

namespace OuterEdge\Layout\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $tableGroups = $installer->getTable('layout_groups');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableGroups) != true) {
            // Create table
            $table = $installer->getConnection()
                ->newTable($tableGroups)
                ->addColumn(
                    'id_group',
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
                    'title',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true, 'default' => null],
                    'Title'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    null,
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
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Created At'
                )
                ->setComment('Layout Group Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }

        $tableType = $installer->getTable('layout_types');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableType) != true) {
            // Create table
            $table = $installer->getConnection()
                ->newTable($tableType)
                ->addColumn(
                    'id_type',
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
                    'title',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true, 'default' => null],
                    'Title'
                )
                ->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Sort Order'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Created At'
                )
                ->setComment('Layout Types Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }

        $tableElements = $installer->getTable('layout_elements');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableElements) != true) {
            // Create table
            $table = $installer->getConnection()
                ->newTable($tableElements)
                ->addColumn(
                    'id_element',
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
                    'fk_group',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false
                    ],
                    'Fk Group'
                )
                ->addColumn(
                    'fk_type',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => true
                    ],
                    'Fk Type'
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
                    null,
                    ['nullable' => true],
                    'Description'
                )
                ->addColumn(
                    'link',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Link'
                )
                ->addColumn(
                    'link_text',
                    Table::TYPE_TEXT,
                    null,
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
                    'overlay_style',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Overlay Style'
                )
                ->addColumn(
                    'overlay_colour',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Overlay Colour'
                )
                ->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Sort Order'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => true ],
                    'Created At'
                )
                ->addForeignKey(
                    $installer->getFkName('layout_elements', 'fk_group', 'layout_groups', 'id_group'),
                    'fk_group',
                    $installer->getTable('layout_groups'),
                    'id_group',
                    Table::ACTION_SET_NULL
                )
                ->addForeignKey(
                    $installer->getFkName('layout_elements', 'fk_type', 'layout_types', 'id_type'),
                    'fk_type',
                    $installer->getTable('layout_types'),
                    'id_type',
                    Table::ACTION_SET_NULL
                )
                ->setComment('Elements Group Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}