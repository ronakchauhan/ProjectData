<?php
namespace Ktpl\Additional\Setup;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    private $customerSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
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
}
