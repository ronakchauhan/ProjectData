<?php

namespace Ktpl\CategoryView\Setup;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;

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
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if ($context->getVersion()
            && version_compare($context->getVersion(), '2.0.0') < 0
        ) {
            /** @var \Magento\Catalog\Setup\CategorySetup $categorySetup */
            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

            $attributeCode = 'block_layout';

            /** Add attribute to category */
            $categorySetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                $attributeCode,
                [
                    'type' => 'text',
                    'label' => 'Block Layout',
                    'input' => 'text',
                    'source' => '',
                    'required' => false,
                    'sort_order' => 70,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Display Settings',
                    'is_used_in_grid' => false,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false
                ]
            );

            /** Add attribute to category group */
            $categorySetup->addAttributeToGroup(
                $entityTypeId,
                $attributeSetId,
                $categorySetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'display-settings'),
                $attributeCode,
                70
            );
        }

        if ($context->getVersion()
            && version_compare($context->getVersion(), '2.0.1') < 0
        ) {
            /** @var \Magento\Catalog\Setup\CategorySetup $categorySetup */
            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

            $attributeCode = 'banner_block';

            /** Add attribute to category */
            $categorySetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                $attributeCode,
                [
                    'type' => 'text',
                    'label' => 'Banner Block',
                    'input' => 'text',
                    'source' => 'Ktpl\CategoryView\Model\Catalog\Category\Attribute\Source\Listing\Section',
                    'required' => false,
                    'sort_order' => 60,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'General Information',
                    'is_used_in_grid' => false,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false
                ]
            );

            /** Add attribute to category group */
            $categorySetup->addAttributeToGroup(
                $entityTypeId,
                $attributeSetId,
                $categorySetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'General Information'),
                $attributeCode,
                70
            );


            $categorySetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                $attributeCode,
                [
                    'type' => 'varchar',
                    'label' => 'Group Attribute Sort',
                    'input' => 'select',
                    'source' => 'Ktpl\CategoryView\Model\Catalog\Category\Attribute\Source\Listing\Sort',
                    'required' => false,
                    'sort_order' => 70,
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
                70
            );
        }
    }
}
