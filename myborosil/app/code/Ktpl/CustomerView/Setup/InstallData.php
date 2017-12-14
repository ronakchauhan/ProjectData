<?php

namespace Ktpl\CustomerView\Setup;

use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Customer setup factory
     *
     * @var \Magento\Customer\Setup\CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * Init
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(\Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory)
    {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $setup->startSetup();

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'contact_number', [
            'type' => 'varchar',
            'label' => 'Contact Number',
            'input' => 'text',
            'required' => 0,
            'sort_order' => 110,
            'visible' => 1,
            'system' => 0,
            'position' => 110
        ]);
        //add attribute to attribute set
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'contact_number');
        $attribute->setData('used_in_forms', ['adminhtml_customer', 'customer_account_create', 'customer_account_edit']);
        $attribute->save();

        $setup->endSetup();
    }
}
