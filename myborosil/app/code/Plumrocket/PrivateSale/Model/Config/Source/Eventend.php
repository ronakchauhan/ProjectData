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


namespace Plumrocket\PrivateSale\Model\Config\Source;

class Eventend extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const NOT_FOUND = 1;
    const DISABLE_ADD_TO_CART = 2;
    const DO_NOTHING = 3;

    const CONFIG_PATH = 'prprivatesale/settings/end_action';

    /**
     * Retrieve all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = [
            [
                'label' => __("Disable Event's Category & Products"),
                'value' => self::NOT_FOUND
            ],
            [
                'label' => __("Leave Event's Category & Products Enabled, but Hide \"Add To Cart\" Buttons"),
                'value' => self::DISABLE_ADD_TO_CART
            ],
            [
                'label' => __('Do nothing'),
                'value' => self::DO_NOTHING
            ],
        ];

        return $options;
    }

    /**
     * Retrieve all options
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }
}
