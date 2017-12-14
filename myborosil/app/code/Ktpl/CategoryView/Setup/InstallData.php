<?php

namespace Ktpl\CategoryView\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Catalog\Setup\CategorySetupFactory;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * Init
     *
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(CategorySetupFactory $categorySetupFactory)
    {
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Magento\Catalog\Setup\CategorySetup $categorySetup */
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

        $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

        $attributeCode = 'listing_layout';

        /** Add attribute to category */
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            $attributeCode,
            [
                'type' => 'varchar',
                'label' => 'Listing Layout',
                'input' => 'select',
                'source' => 'Ktpl\CategoryView\Model\Catalog\Category\Attribute\Source\Listing\Layout',
                'required' => false,
                'sort_order' => 60,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Display Settings',
                'default' => 'default_layout',
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'used_in_product_listing' => true,
            ]
        );

        /** Add attribute to category group */
        $categorySetup->addAttributeToGroup(
            $entityTypeId,
            $attributeSetId,
            $categorySetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'display-settings'),
            $attributeCode,
            60
        );
    }
}
