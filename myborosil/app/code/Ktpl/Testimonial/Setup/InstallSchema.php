<?php
namespace Ktpl\Testimonial\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (!$installer->tableExists('ktpl_testimonials')) {
            
            /**
             *  Create Testimonial table 'ktpl_testimonials'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('ktpl_testimonials')
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Increment ID'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Customer Name'
            )->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Testimonial Title'
            )->addColumn(
                'description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => false],
                'desctiprion'
            )->addColumn(
                'date',
                 Table::TYPE_DATETIME,
                 null, 
                 ['nullable' => false], 'Testimonial Date'
            )->addColumn(
                'created_at',
                 Table::TYPE_DATETIME,
                 null, 
                 ['nullable' => false], 'Created Date'
            )->addColumn(
                'updated_at',
                 Table::TYPE_DATETIME,
                 null, 
                 ['nullable' => false], 'Updated Date'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['nullable' => false],
                'Status'
            )->setComment(
                'Customer Testimonial Table'
            );
            
            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('ktpl_testimonials_store')) {
            /**
             * Create Testimonial Store table 'ktpl_testimonials_store'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('ktpl_testimonials_store')
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'primary' => true],
                'Testimonial ID'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Store ID'
            )->addIndex(
                $installer->getIdxName('ktpl_testimonials_store', ['store_id']),
                ['store_id']
            )->addForeignKey(
                $installer->getFkName('ktpl_testimonials_store', 'entity_id', 'ktpl_testimonials', 'entity_id'),
                'entity_id',
                $installer->getTable('ktpl_testimonials'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName('ktpl_testimonials_store', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment(
                'Ktpl Testimonial To Store Linkage Table'
            );
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
