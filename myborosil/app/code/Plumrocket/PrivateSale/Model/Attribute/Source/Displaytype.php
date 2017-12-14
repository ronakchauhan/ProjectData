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


namespace Plumrocket\PrivateSale\Model\Attribute\Source;

class Displaytype extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    const DISPLAY_TYPE_ALL = 0;
    const DISPLAY_CATEGORY_PAGE = 1;
    const DISPLAY_PRODUCT_PAGE = 2;
    const DISPLAY_HOMEPAGE = 3;

    /**
     * Retrieve all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = [
            [
                'label' => __('Category Pages'),
                'value' => self::DISPLAY_CATEGORY_PAGE
            ],
            [
                'label' => __('Product Pages'),
                'value' => self::DISPLAY_PRODUCT_PAGE
            ],
            [
                'label' => __('Private Sales Homepage'),
                'value' => self::DISPLAY_HOMEPAGE
            ]
        ];

        return $options;
    }
}
