<?php

namespace Ktpl\ImageOptions\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Catalog\Model\ResourceModel\Product\Gallery;

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
                $setup->getTable(Gallery::GALLERY_TABLE), 'isbigimage', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            'unsigned' => true,
            'nullable' => false,
            'default' => 0,
            'comment' => 'Set Big Image'                ]
        );


        $installer->endSetup();
    }
}
