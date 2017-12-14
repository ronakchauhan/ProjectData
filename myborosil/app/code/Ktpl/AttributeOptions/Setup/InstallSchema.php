<?php

namespace Ktpl\AttributeOptions\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $setup->getConnection()->addColumn(
                $setup->getTable('catalog_eav_attribute'), 'is_user_guide', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'comment' => 'Set User Guied'                ]
        );

        $setup->getConnection()->addColumn(
                $setup->getTable('catalog_eav_attribute'), 'swatch_to_dropdown', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'comment' => 'Convert Swatch to Dropdown'                ]
        );

        $installer->endSetup();
    }
}
