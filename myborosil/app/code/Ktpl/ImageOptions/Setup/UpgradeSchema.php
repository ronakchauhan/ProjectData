<?php

namespace Ktpl\ImageOptions\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Catalog\Model\ResourceModel\Product\Gallery;

/**
 * Upgrade the Cms module DB scheme
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->addExtraFields($setup);
        }
    }

    /**
     * Add css fields
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     */
    protected function addExtraFields(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable(Gallery::GALLERY_TABLE), 'partsimage', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            'unsigned' => true,
            'nullable' => false,
            'default' => 0,
            'comment' => 'Use as Product Parts Image'                ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable(Gallery::GALLERY_TABLE), 'hidefromlisting', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            'unsigned' => true,
            'nullable' => false,
            'default' => 0,
            'comment' => 'Hide From Product Listing Page'                ]
        );

        return $this;
    }
}
