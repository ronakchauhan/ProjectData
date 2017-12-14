<?php

namespace Ktpl\ProductView\Setup;

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
            $this->addOptionLabelFieldToBundleOptionTable($setup);
        }
    }

    /**
     * Add Block Layout Position Field
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     */
    protected function addOptionLabelFieldToBundleOptionTable(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('catalog_product_bundle_option'),
            'option_label',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Option Label'
            ]
        );
        return $this;
    }
}
