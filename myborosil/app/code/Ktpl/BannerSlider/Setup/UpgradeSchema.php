<?php

namespace Ktpl\BannerSlider\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

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
            $setup->getTable('ktpl_banner'),
            'custom_class',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Custom Class'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('ktpl_banner'),
            'custom_css',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Custom CSS'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('ktpl_banner'),
            'video_status',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'length' => 6,
                'nullable' => false,
                'comment' => 'Status Of Video Frame'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('ktpl_banner'),
            'video_url',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Video URL'
            ]
        );

         $setup->getConnection()->addColumn(
            $setup->getTable('ktpl_banner'),
            'sortorder',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'length' => 11,
                'nullable' => false,
                "default" => 0,
                'comment' => 'Sort Order'
            ]
        );
        return $this;
    }
}
