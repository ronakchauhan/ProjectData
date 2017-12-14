<?php

namespace Ktpl\SectionView\Setup;

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
        if (version_compare($context->getVersion(), '2.0.0', '<')) {
            $this->addCSSFields($setup);
        }

        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            $this->addVideoFields($setup);
        }

        if (version_compare($context->getVersion(), '2.0.2', '<')) {
            $this->addImageFields($setup);
        }
    }

    /**
     * Add css fields
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     */
    protected function addCSSFields(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('ktpl_section'),
            'custom_class',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Custom Class'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('ktpl_section'),
            'custom_css',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Custom CSS'
            ]
        );
        return $this;
    }
/**
     * Add css fields
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     */
    protected function addImageFields(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('ktpl_section'),
            'image_position',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'length' => 11,
                'nullable' => true,
                'comment' => 'Position of Image section as Top or in List'
            ]
        );
        return $this;
    }

    /**
     * Add css fields
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     */
    protected function addVideoFields(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('ktpl_section'),
            'video_status',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'length' => 6,
                'nullable' => false,
                'comment' => 'Status Of Video Frame'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('ktpl_section'),
            'video_url',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Video URL'
            ]
        );
        return $this;
    }
}
