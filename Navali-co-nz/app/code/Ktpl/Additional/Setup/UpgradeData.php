<?php
namespace Ktpl\Additional\Setup;

use Magento\Customer\Model\Customer;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
 
class UpgradeData implements UpgradeDataInterface{

    protected $eavSetupFactory;

    public function __construct(
            \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
            \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
        )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->customerSetupFactory = $customerSetupFactory;
    }

     public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if ($context->getVersion()
            && version_compare($context->getVersion(), '1.0.1') < 0
        ) {
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerSetup->addAttribute('customer_address', 'suburb', [
                'label' => 'Suburb',
                'input' => 'text',
                'type' => 'varchar',
                'source' => '',
                'required' => true,
                'position' => 70,
                'visible' => true,
                'system' => false,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'backend' => ''
            ]);

            
            $attribute = $customerSetup->getEavConfig()->getAttribute('customer_address', 'suburb')
                ->addData(['used_in_forms' => [
                    'adminhtml_customer_address',
                    'customer_address_edit',
                    'customer_register_address'
                ]]);
            $attribute->save();
        }
        else if($context->getVersion()
            && version_compare($context->getVersion(), '1.0.2') < 0
        ){
            $installer = $setup;

            $installer->startSetup();
            
            /**
             * Create table 'banners_slider'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bag_query_request')
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
                'email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Customer Email'
            )->addColumn(
                'Comment',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Customer Comment'
            )->addColumn(
                'bag_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Customer Bag Image'
            )->setComment(
                'Customer Bags Table'
            );
            $installer->getConnection()->createTable($table);
        }else if($context->getVersion()
            && version_compare($context->getVersion(), '1.0.3') < 0
        ){
            $installer = $setup;

            $installer->startSetup();
            
            /**
             * Create table 'banners_slider'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bag_query_request')
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
                'email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Customer Email'
            )->addColumn(
                'Comment',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Customer Comment'
            )->addColumn(
                'bag_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Customer Bag Image'
            )->setComment(
                'Customer Bags Table'
            );
            $installer->getConnection()->createTable($table);
        }

        $setup->endSetup();

    }
}