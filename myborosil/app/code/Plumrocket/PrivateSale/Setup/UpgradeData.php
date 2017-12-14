<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket Private Sales and Flash Sales v4.x.x
 * @copyright   Copyright (c) 2017 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\PrivateSale\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Model\Group;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Magento\Eav\Model\Entity\Attribute
     */
    protected $eavAttributeFactory;

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute
     */
    protected $eavAttribute;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $customerGroup;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    const PRIVATE_YES = 2;

    /**
     * @param   \Magento\Eav\Model\Entity\AttributeFactory $eavAttributeFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        \Magento\Eav\Model\Entity\AttributeFactory $eavAttributeFactory,
        \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute $eavAttribute,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\App\State $state
    ) {
        $this->eavAttribute = $eavAttribute;
        $this->resourceConnection = $resourceConnection;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavAttributeFactory = $eavAttributeFactory;
        $this->customerGroup = $customerGroup;
        $this->state = $state;
        try {
            $this->state->setAreaCode('adminhtml');
        } catch (LocalizedException $e) {
        }
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        $setup->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '4.2.0', '<')) {

            $eavSetup->addAttribute(
                 \Magento\Catalog\Model\Category::ENTITY,
                'privatesale_private_event',
                array(
                    'group'         => 'Private Sale & Flash Sale Event',
                    'input'         => 'select',
                    'type'          => 'int',
                    'label'         => 'Private Event',
                    'backend'       => '',
                    'visible'       => 1,
                    'required'      => 0,
                    'visible_on_front' => 1,
                    "sort_order" => 50,
                    "source" =>     "\Plumrocket\PrivateSale\Model\Attribute\Source\Category\PrivateEvent",
                    "backend"=>     "",
                    "note" => __('If enabled, this category will become a private sale event and will be accessible only for selected Customer Groups. Please note that if you have "Splash Page" enabled, all categories will automatically become private events.'),
                    'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                ),
                60
            );

            $eavSetup->addAttribute(
                 \Magento\Catalog\Model\Category::ENTITY,
                'privatesale_event_landing',
                array(
                    'group'         => 'Private Sale & Flash Sale Event',
                    'input'         => 'select',
                    'type'          => 'text',
                    'label'         => 'Private Event Landing Page',
                    'backend'       => '',
                    "sort_order" => 60,
                    'visible'       => 1,
                    'required'      => 0,
                    'visible_on_front' => 1,
                    "source" =>     "\Plumrocket\PrivateSale\Model\Attribute\Source\EventLanding",
                    "backend"=>     "",
                    "note" => __('Display this Landing Page to Customer Groups you did not select above. Customers without access to this private event will be redirected here.'),
                    'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                ),
                70
            );

            $eavSetup->addAttribute(
                 \Magento\Catalog\Model\Category::ENTITY,
                'privatesale_restrict_cgroup',
                array(
                    'group'         => 'Private Sale & Flash Sale Event',
                    'input'         => 'multiselect',
                    'type'          => 'varchar',
                    'label'         => 'Accessible for Customer Groups',
                    "sort_order" => 60,
                    'visible'       => 1,
                    'required'      => 0,
                    'visible_on_front' => 1,
                    "source" =>     "\Plumrocket\PrivateSale\Model\Attribute\Source\Customergroups",
                    "backend"=>     "\Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend",
                    "note" => __('Please select Customer Group(s) that will have access to this private event.'),
                    'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                ),
                80
            );

            // upgrade product attribute

            $eavSetup->addAttribute(
                 \Magento\Catalog\Model\Product::ENTITY,
                'privatesale_private_event',
                [
                    'group'         => 'Private Sale Event',
                    'input'         => 'select',
                    'type'          => 'int',
                    'label'         => 'Private Event',
                    'backend'       => '',
                    'visible'       => 1,
                    'required'      => 0,
                    'visible_on_front' => 1,
                    "sort_order" => 60,
                    "source" =>     "\Plumrocket\PrivateSale\Model\Attribute\Source\Product\PrivateEvent",
                    "backend"=>     "",
                    "note" => __('If enabled, this product will become a private sale event and will be accessible only for selected Customer Groups. Please note that if you have "Splash Page" enabled, all categories & products will automatically become private events.'),
                    'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                ],
                60
            );

            $eavSetup->addAttribute(
                 \Magento\Catalog\Model\Product::ENTITY,
                'privatesale_event_landing',
                [
                    'group'         => 'Private Sale Event',
                    'input'         => 'select',
                    'type'          => 'text',
                    'label'         => 'Private Event Landing Page',
                    'backend'       => '',
                    "sort_order" => 70,
                    'visible'       => 1,
                    'required'      => 0,
                    'visible_on_front' => 1,
                    "source" =>     "\Plumrocket\PrivateSale\Model\Attribute\Source\EventLanding",
                    "backend"=>     "",
                    "note" => __('Display this Landing Page to Customer Groups you did not select above. Customers without access to this private event will be redirected here.'),
                    'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                ],
                70
            );

            $eavSetup->addAttribute(
                 \Magento\Catalog\Model\Product::ENTITY,
                'privatesale_restrict_cgroup',
                [
                    'group'         => 'Private Sale Event',
                    'input'         => 'multiselect',
                    'type'          => 'varchar',
                    'label'         => 'Accessible for Customer Groups',
                    'visible'       => 1,
                    'required'      => 0,
                    'visible_on_front' => 1,
                    "sort_order" => 60,
                    "source" =>     "\Plumrocket\PrivateSale\Model\Attribute\Source\Customergroups",
                    "backend"=>     "\Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend",
                    "note" => __('Please select Customer Group(s) that will have access to this private event.'),
                    'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                ],
                60
            );

            $attribute = $this->eavAttributeFactory->create()
                ->loadByCode(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'privatesale_restrict_cgroup'
                );
            $attribute->setData('used_in_product_listing', 1)->save();

            $optionArray = $this->customerGroup->toOptionArray();
            unset($optionArray[Group::NOT_LOGGED_IN_ID]);

            foreach ($optionArray as  $option) {
                $customerGroupIds[] = $option['value'];
            }

            $customerGroupIds = implode(',', $customerGroupIds);

            $connection = $this->resourceConnection->getConnection();

            foreach (['category', 'product'] as $type) {

                $tableInfo = $connection->describeTable(
                    $this->resourceConnection->getTableName('catalog_' . $type . '_entity_text')
                );

                $entityIdColumnName =  isset($tableInfo['entity_id']) ? 'entity_id' : 'row_id';
                $entityColumnName = ['value_id', 'attribute_id', 'store_id', $entityIdColumnName, 'value'];

                $select = $connection->select()
                    ->from(['tbl' => $this->resourceConnection->getTableName('catalog_' . $type . '_entity_text')],
                        [new \Zend_Db_Expr('NULL AS value_id, attribute_id, store_id, ' . $entityIdColumnName . ', value')]
                    )
                    ->where(
                        "tbl.attribute_id = " . $this->eavAttribute->getIdByCode('catalog_' . $type . '', 'privatesale_private_event')
                    );

                $connection->query(
                    $select->insertFromSelect(
                        $this->resourceConnection->getTableName('catalog_' . $type . '_entity_int'),
                        $entityColumnName
                    )
                );

                $connection->delete(
                    $this->resourceConnection->getTableName('catalog_' . $type . '_entity_text'),
                    "attribute_id = " . $this->eavAttribute->getIdByCode('catalog_' . $type . '', 'privatesale_private_event')
                );

                $select = $connection->select()
                    ->from(['tbl' => $this->resourceConnection->getTableName('catalog_' . $type . '_entity_int')], [
                        new \Zend_Db_Expr(
                            "NULL AS value_id, " .
                            $this->eavAttribute->getIdByCode('catalog_' . $type . '', 'privatesale_restrict_cgroup') .
                            " AS attribute_id, store_id, $entityIdColumnName,
                            '$customerGroupIds' AS value"
                        )
                    ])
                    ->where(
                        "tbl.attribute_id = " .  $this->eavAttribute->getIdByCode('catalog_' . $type . '', 'privatesale_private_event'),
                        "tbl.value = " . self::PRIVATE_YES
                    );

                $connection->query(
                    $select->insertFromSelect(
                        $this->resourceConnection->getTableName('catalog_' . $type . '_entity_varchar'),
                        $entityColumnName
                    )
                );

            }
        }

        $setup->endSetup();
    }

}
