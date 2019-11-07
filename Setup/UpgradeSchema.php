<?php

namespace OuterEdge\Layout\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->addTemplateFileColumnToTemplateTable($setup);
        }

        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $this->createLayoutGroupStoreTable($setup);
        }

        if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $this->addShowInGridColumnToLayoutElementEntity($setup);
        }

        $setup->endSetup();
    }

    /**
     * Add the column 'template_file' to the layout_group table
     *
     * @param SchemaSetupInterface $setup
     *
     * @return void
     */
    private function addTemplateFileColumnToTemplateTable(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();

        $tableItem = $setup->getTable('layout_group');
        if ($connection->isTableExists($tableItem) == true) {
            $connection->addColumn(
                $tableItem,
                'template_file',
                [
                    'type'     => Table::TYPE_TEXT,
                    'nullable' => true,
                    'default'  => null,
                    'comment'  => 'Template Widget File'
                ]
            );
        }
    }

    /**
     * Create layout_group_store table
     *
     * @param SchemaSetupInterface $setup
     *
     * @return void
     */
    private function createLayoutGroupStoreTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()
            ->newTable($setup->getTable('layout_group_store'))
            ->addColumn(
                'group_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Group Id'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Store Id'
            )
            ->addIndex(
                $setup->getIdxName('layout_group_store', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName('layout_group_store', 'group_id', 'layout_group', 'entity_id'),
                'group_id',
                $setup->getTable('layout_group'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName('layout_group_store', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Layout Group Store');
        $setup->getConnection()->createTable($table);
    }

    /**
     * Add the column 'store_id' to the layout_group table
     *
     * @param SchemaSetupInterface $setup
     *
     * @return void
     */
    private function addStoreColumnToLayoutGroup(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();

        $tableItem = $setup->getTable('layout_group');
        if ($connection->isTableExists($tableItem) == true) {
            $connection->addColumn(
                $tableItem,
                'store_id',
                [
                    'type'     => Table::TYPE_SMALLINT,
                    'nullable' => true,
                    'default'  => 0,
                    'comment'  => 'Store Id'
                ]
            )->addIndex(
                $installer->getIdxName('layout_group', ['store_id']),
                ['store_id']
            )->addForeignKey(
                $installer->getFkName('layout_group', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                Table::ACTION_SET_NULL
            );
        }
    }

    /**
     * Add show_in_grid option to layout_element_entity table
     *
     * @param SchemaSetupInterface $setup
     *
     * @return void
     */
    private function addShowInGridColumnToLayoutElementEntity(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();

        $tableItem = $setup->getTable('layout_element_entity');
        if ($connection->isTableExists($tableItem) == true) {
            $connection->addColumn(
                $tableItem,
                'show_in_grid',
                [
                    'type'     => Table::TYPE_TEXT,
                    'nullable' => true,
                    'default'  => null,
                    'comment'  => 'Field to show In Grid'
                ]
            );
        }
    }
}
