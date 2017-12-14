<?php

namespace Ktpl\PageView\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.0', '<')) {
            $this->addBlockLayoutPositionField($setup);
        }

        if (version_compare($context->getVersion(), '3.0.0', '<')) {
            $this->addScrollableField($setup);
        }
    }

    /**
     * Add Block Layout Position Field
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     */
    protected function addBlockLayoutPositionField(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('cms_page'),
            'block_layout_position',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Block Layout Position'
            ]
        );
        return $this;
    }

    /**
     * Add Scrollable Field
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     */
    protected function addScrollableField(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('cms_page'),
            'scrollable',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Scrollable Page'
            ]
        );
        return $this;
    }
}
