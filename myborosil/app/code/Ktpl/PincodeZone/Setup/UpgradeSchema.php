<?php

namespace Ktpl\PincodeZone\Setup;

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
            $this->createZonePincodeTable($setup);
        }
    }

    /**
     * Add meta title
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     */
    protected function createZonePincodeTable(SchemaSetupInterface $setup)
    {

        if (!$setup->tableExists('ktpl_pincode_zone_relation')) {
            $table = $setup->getConnection()->newTable($setup->getTable('ktpl_pincode_zone_relation')
                )->addColumn(
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'Entity ID'
                )->addColumn(
                    'zone_id', 
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    10, 
                    ['nullable' => false, 'primary' => true]
                )->addColumn(
                    'pincode_id', 
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    10, 
                    ['nullable' => false, 'unsigned' => true], 
                    'Pincode Id'
                )->addForeignKey(
                    $setup->getFkName(
                        'ktpl_pincode_zone',
                        'entity_id',
                        'ktpl_pincode_zone_relation',
                        'zone_id'
                    ),
                    'zone_id',
                    $setup->getTable('ktpl_pincode_zone'),
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->setComment('Zone relation to pincodes.');

            $setup->getConnection()->createTable($table);
        }
        
        return $this;
    }
}
