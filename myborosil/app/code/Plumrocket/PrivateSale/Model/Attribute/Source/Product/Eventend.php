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


namespace Plumrocket\PrivateSale\Model\Attribute\Source\Product;

class Eventend extends \Plumrocket\PrivateSale\Model\Attribute\Source\Category\Eventend
{
	const USE_CATEGORY = 5;

    /**
     * Retrieve all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        //For product used parent values, but changed labels
        $options = [
            [
                'label' => __('Use category Settings'),
                'value' => self::USE_CATEGORY
            ],
            [
                'label' => __("Disable Current Product"),
                'value' => parent::NOT_FOUND
            ],
            [
                'label' => __("Leave Current Product Enabled, but Hide \"Add To Cart\" Button"),
                'value' => parent::DISABLE_ADD_TO_CART
            ],
            [
                'label' => __('Do nothing'),
                'value' => parent::DO_NOTHING
            ],
        ];

        return $options;
    }
}
