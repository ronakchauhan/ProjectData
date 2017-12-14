<?php

namespace Ktpl\ProductView\Setup;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;

/**
 * Upgrade Data script
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            $this->addExtraField($setup);
        }

        if (version_compare($context->getVersion(), '2.0.2', '<')) {
            $this->addCompareProductList($setup);
        }
    }

    public function addCompareProductList($setup)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'compare_product_list',
            [
                'type' => 'text',                
                'frontend' => '',
                'label' => 'Compare Product List',
                'input' => 'multiselect',
                'class' => '',
                'backend' => 'Ktpl\ProductView\Model\Catalog\Product\Attribute\Backend\Compare\Products',
                'source' => 'Ktpl\ProductView\Model\Catalog\Product\Attribute\Source\Compare\Products',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'group' => 'Layout Settings',
                'note' => 'This option will only use with "APPLIANCES" layout.'
            ]
        );
    }


    public function addExtraField($setup)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        
        $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY, 'product_block', [
            'type' => 'text',
            'label' => 'Product Block',
            'input' => 'text',
            'required' => false,
            'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
            'group' => 'Content',
            'used_in_product_listing' => false,
            'visible_on_front' => false,
            'class' => 'product-block-custom-section',
            'sort' => 20,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
                ]
        );
    }
}
