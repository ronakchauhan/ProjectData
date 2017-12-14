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

class Beforeeventstart extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    const DISPLAY_YES = 1;
    const DISPLAY_NO = 2;

    const CONFIG_PATH = 'prprivatesale/settings/before_start_action';

    /**
     * Retrieve all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = [
            [
                'label' => __('Yes'),
                'value' => self::DISPLAY_YES
            ],
            [
                'label' => __('No'),
                'value' => self::DISPLAY_NO
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
