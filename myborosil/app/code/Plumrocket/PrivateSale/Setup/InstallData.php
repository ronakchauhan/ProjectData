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
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\PrivateSale\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $_eavSetupFactory;

    /**
     * Email template
     * @var Plumrocket\PrivateSale\Model\Emailtemplate
     */
    private $emailtemplate;

    /**
     * Email template data
     * @var EmailTemplateData
     */
    protected $emailTemplateData;

    /**
     * Page factory
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $pageFactory;

    /**
     * Product meta data
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * InstallData constructor.
     *
     * @param EavSetupFactory                                    $eavSetupFactory
     * @param \Plumrocket\PrivateSale\Model\EmailtemplateFactory $emailtemplate
     * @param \Magento\Framework\App\ProductMetadataInterface    $productMetadata
     * @param \Magento\Cms\Model\PageFactory                     $pageFactory
     * @param EmailTemplateData                                  $emailTemplateData
     * @param \Magento\Framework\App\State                       $state
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        \Plumrocket\PrivateSale\Model\EmailtemplateFactory $emailtemplate,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Cms\Model\PageFactory $pageFactory,
        EmailTemplateData $emailTemplateData,
        \Magento\Framework\App\State $state
    ) {
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->pageFactory = $pageFactory;
        $this->productMetadata = $productMetadata;
        $this->emailtemplate = $emailtemplate;
        $this->emailTemplateData = $emailTemplateData;
        $state->setAreaCode('adminhtml');
    }

    /**
     * Install Data
     * @param  ModuleDataSetupInterface $setup
     * @param  ModuleContextInterface   $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);

        $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
        $attributeSetId   = $eavSetup->getDefaultAttributeSetId($entityTypeId);
        $attributeGroupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'Private Sale & Flash Sale Event');

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'privatesale_email_image',
            [
                'type' => 'varchar',
                'frontend' => '',
                'label' => 'Newsletter Image',
                'input' => 'image',
                'group' => 'General Information',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'default' => 0,
                'position' => 20
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'privatesale_event_image',
            [
                'type' => 'varchar',
                'frontend' => '',
                'group' => 'General Information',
                'label' => 'Event Image',
                'input' => 'image',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'default' => 0,
                'position' => 22
            ]
        );

        $attributeGroupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'Private Sale & Flash Sale Event');

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'privatesale_date_start',
            [
                'type' => 'datetime',
                'frontend' => '',
                'label' => 'Start Date',
                'group' => 'Private Sale & Flash Sale Event',
                'input' => 'date',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'backend'   => 'Magento\Eav\Model\Entity\Attribute\Backend\Datetime',
                'required' => false,
                'position' => 10
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'privatesale_date_end',
            [
                'type' => 'datetime',
                'frontend' => '',
                'group' => 'Private Sale & Flash Sale Event',
                'label' => 'End Date',
                'input' => 'date',
                'backend'   => 'Magento\Eav\Model\Entity\Attribute\Backend\Datetime',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'default' => null,
                'position' => 20
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'privatesale_before_event_start',
            [
                'type' => 'text',
                'frontend' => '',
                'label' => 'Display Products Before Event Starts',
                'input' => 'select',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'group' => 'Private Sale & Flash Sale Event',
                'default' => 0,
                "source" =>  '\Plumrocket\PrivateSale\Model\Attribute\Source\Category\Beforeeventstart',
                'position' => 40
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'privatesale_event_end',
            [
                'type' => 'text',
                'group' => 'Private Sale & Flash Sale Event',
                'frontend' => '',
                'label' => 'When Event Ends',
                'input' => 'select',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'default' => 0,
                "source" =>  '\Plumrocket\PrivateSale\Model\Attribute\Source\Category\Eventend',
                'position' => 50
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'privatesale_private_event',
            array(
                'group'         => 'Private Sale & Flash Sale Event',
                'input'         => 'select',
                'type'          => 'text',
                'label'         => 'Private Event',
                'backend'       => '',
                'visible'       => 1,
                'required'      => 0,
                'visible_on_front' => 1,
                "sort_order" => 50,
                "source" =>     "\Plumrocket\PrivateSale\Model\Attribute\Source\Category\PrivateEvent",
                "note" => __('If enabled, this category will become a private sale event and will not be accessible for not-logged in visitors. Please note that if you have "Splash Page" enabled, all categories will automatically become private events.'),
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
                "note" => __('Display this page to all not-logged in visitors'),
                'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            ),
            70
        );


        //Install attributes for product


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'privatesale_date_start',
            $this->getDataAttribute('Start Date')
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'privatesale_date_end',
            $this->getDataAttribute('End Date')
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'privatesale_before_event_start',
            [
                'type' => 'text',
                'frontend' => '',
                'label' => 'Display Products Before Event Starts',
                'input' => 'select',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'group' => 'Flash Sale Event',
                'required' => false,
                'default' => 0,
                "source" =>  '\Plumrocket\PrivateSale\Model\Attribute\Source\Product\Beforeeventstart',
                'position' => 40
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'privatesale_event_end',
            [
                'type' => 'text',
                'frontend' => '',
                'label' => 'When Event Ends',
                'input' => 'select',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'default' => 0,
                'group' => 'Flash Sale Event',
                "source" =>  '\Plumrocket\PrivateSale\Model\Attribute\Source\Product\Eventend',
                'position' => 50
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'privatesale_private_event',
            [
                'group'         => 'Private Sale Event',
                'input'         => 'select',
                'type'          => 'text',
                'label'         => 'Private Event',
                'backend'       => '',
                'visible'       => 1,
                'required'      => 0,
                'visible_on_front' => 1,
                "sort_order" => 60,
                "source" =>     "\Plumrocket\PrivateSale\Model\Attribute\Source\Product\PrivateEvent",
                "note" => __('If enabled, this product will become a private sale event and will not be accessible for not-logged in visitors. Please note that if you have "Splash Page" enabled, all categories & products will automatically become private events.'),
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
                "note" => __('Display this page to all not-logged in visitors'),
                'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            ],
            70
        );

        $this->emailtemplate->create()
            ->setData(
                [
                    'name' => 'One Event in Row',
                    'template' => $this->emailTemplateData->getDefaultEmailTemplate(),
                    'list_template' => $this->emailTemplateData->getListTemplateOne(),
                    'list_template_date_format' => 'm/d/Y',
                    'list_layout' => '1'
                ]
            )->save();

        $this->emailtemplate->create()
            ->setData(
                [
                    'name' => 'Two Events in Row',
                    'template' => $this->emailTemplateData->getDefaultEmailTemplate(),
                    'list_template' => $this->emailTemplateData->getListTemplateTwo(),
                    'list_template_date_format' => 'm/d/Y',
                    'list_layout' => '2'
                ]
            )->save();

        $this->emailtemplate->create()
            ->setData(
                [
                    'name' => 'Three Events in Row',
                    'template' => $this->emailTemplateData->getDefaultEmailTemplate(),
                    'list_template' => $this->emailTemplateData->getListTemplateThree(),
                    'list_template_date_format' => 'm/d/Y',
                    'list_layout' => '3'
                ]
            )->save();


        $this->pageFactory->create()
            ->setData(
                [
                    'is_active' => false,
                    'identifier' => 'flash-sales-homepage',
                    'title' => 'Private Sales Homepage',
                    'layout_update_xml' => $this->getHomepageXml(),
                    'page_layout' => '1column'

                ]
            )->save();

    }

    /**
     * Retrieve homepage xml
     * @return string
     */
    private function getHomepageXml()
    {
        return '
        <referenceContainer name="after.body.start">
            <block class="Magento\Framework\View\Element\Template" name="privatesale.homepage.init" template="Plumrocket_PrivateSale::homepage/init.phtml" />
            <block class="Plumrocket\PrivateSale\Block\Init" name="privatesale.init" template="Plumrocket_PrivateSale::init.phtml" />
        </referenceContainer>

        <referenceContainer name="content">
            <block class="Magento\Framework\View\Element\Template" name="privatesale.homepage.view" template="Plumrocket_PrivateSale::homepage/view.phtml">
                <block class="Plumrocket\PrivateSale\Block\Homepage" name="homepage.events" template="Plumrocket_PrivateSale::homepage/events.phtml">
                    <block class="Plumrocket\PrivateSale\Block\Homepage\Event\Item" name="homepage.event.item" template="Plumrocket_PrivateSale::homepage/event/item/default.phtml" />
                    <block class="Plumrocket\PrivateSale\Block\Homepage\DefaultEvent" name="homepage.default" template="Plumrocket_PrivateSale::homepage/event/default.phtml" />
                    <block class="Plumrocket\PrivateSale\Block\Homepage\Group" name="homepage.group" template="Plumrocket_PrivateSale::homepage/event/group.phtml" />
                    <block class="Plumrocket\PrivateSale\Block\Homepage\Comingsoon" name="homepage.coming.soon" template="Plumrocket_PrivateSale::homepage/event/coming_soon.phtml">
                        <action method="setBlockTitle">
                            <argument name="title" xsi:type="string">Coming Soon</argument>
                        </action>
                    </block>
                    <block class="Plumrocket\PrivateSale\Block\Homepage\Endingsoon" name="homepage.ending.soon" template="Plumrocket_PrivateSale::homepage/event/default.phtml">
                        <action method="setBlockTitle">
                            <argument name="title" xsi:type="string">Ending Soon</argument>
                        </action>
                    </block>

                    <action method="setComingSoonDays">
                        <argument name="days" xsi:type="string">3</argument>
                    </action>
                    <action method="setEndingSoonDays">
                        <argument name="days" xsi:type="string">3</argument>
                    </action>
                    <action method="setEventItemBlock">
                        <argument name="days" xsi:type="string">homepage.event.item</argument>
                    </action>
                </block>
            </block>
        </referenceContainer>';
    }

    /**
     * Add some data to datetime attribute
     * It use only for Magento 2.0.x
     */
    private function getDataAttribute($label)
    {
        $version = $this->productMetadata->getVersion();

        $data = [
            'type' => 'datetime',
            'frontend' => '',
            'group' => 'Flash Sale Event',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'required' => false,
            'position' => 10
        ];

        if (version_compare($version, '2.1.0', '<')) {
            $data = array_merge($data, [
                'backend'   => 'Magento\Eav\Model\Entity\Attribute\Backend\Datetime',
                'label' => $label,
                'input' => 'date',
                'visible' => true,
            ]);
        }
        return $data;
    }

}
