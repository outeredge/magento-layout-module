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

        $setup->endSetup();
    }

    /**
     * Add the column 'template_file' to the layout_emplate table
     *
     * @param SchemaSetupInterface $setup
     *
     * @return void
     */
    private function addTemplateFileColumnToTemplateTable(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();

        $tableItem = $setup->getTable('layout_template');
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
}
