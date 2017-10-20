<?php
namespace OuterEdge\Layout\Setup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    private $elementSetupFactory;
    
    public function __construct(
        \OuterEdge\Layout\Setup\ElementSetupFactory $elementSetupFactory
    ) {
        $this->elementSetupFactory = $elementSetupFactory;
    }
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $elementEntity = \OuterEdge\Layout\Model\Element::ENTITY;
        $elementSetup = $this->elementSetupFactory->create(['setup' => $setup]);
        $elementSetup->installEntities();
        $elementSetup->addAttribute(
            $elementEntity,
            'link',
            ['type' => 'text']
        );
        $setup->endSetup();
    }
}