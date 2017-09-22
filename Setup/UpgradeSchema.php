<?php

namespace OuterEdge\Layout\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
       
       $table = $setup->getConnection()->newTable(
            $setup->getTable('layout_template')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'code',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => null],
            'Template Code'
        )->addColumn(
            'sort_order',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '0'],
            'Sort Order'
        )->setComment(
            'Layout Template Table'
        );
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable('layout_template_fields'))
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity ID'
            )
            ->addColumn(
                'template_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Template FK'
            )
            ->addColumn(
                'label',
                Table::TYPE_TEXT,
                64,
                [],
                'Label'
            )
            ->addColumn(
                'type',
                 Table::TYPE_TEXT,
                64,
                [],
                'Type'
            )
            ->addColumn(
                'sort_order',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Sort Order'
            )
            ->addForeignKey(
                $setup->getFkName('layout_template_fields', 'template_id', 'layout_template', 'entity_id'),
                'template_id',
                $setup->getTable('layout_template'),
                'entity_id',
                Table::ACTION_CASCADE
            )->setComment('Layout Element Fields Table');
        $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }
}

