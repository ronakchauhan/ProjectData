<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\ShopbyBrand\Observer\Admin;

use Amasty\Shopby\Model\OptionSetting;
use Magento\Catalog\Model\Category\Attribute\Source\Page;
use Magento\Framework\Data\Form;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class OptionFormFeatured
 * @package Amasty\ShopbyBrand\Observer\Admin
 * @author Evgeni Obukhovsky
 */
class OptionFormFeatured implements ObserverInterface
{
    /** @var Page */
    protected $page;

    /** @var ObjectManagerInterface */
    protected $_objectManager;

    public function __construct(Page $page, ObjectManagerInterface $objectManager)
    {
        $this->page = $page;
        $this->_objectManager = $objectManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var Form $form */
        $form = $observer->getData('form');

        $featuredFieldset = $form->addFieldset('featured_fieldset', ['legend' => __('Slider Options'), 'class'=>'form-inline']);
        $listYesNo = $this->_objectManager->create('Magento\Config\Model\Config\Source\Yesno')->toOptionArray();

        $featuredFieldset->addField(
            'is_featured',
            'select',
            ['name' => 'is_featured', 'label' => __('Show in Brand Slider'), 'title' => __('Show in Brand Slider'), 'values'=>$listYesNo]
        );

        $featuredFieldset->addField(
            'slider_position',
            'text',
            ['name' => 'slider_position', 'label' => __('Position in Slider'), 'title' => __('Position in Slider')]
        );
    }
}
