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

class Splashpagestatus extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    const STATUS_DISABLED = 0;
    const STATUS_ENABLED_SPLASH = 1;
    const STATUS_ENABLED_DEFAULT = 2;

    /**
     * Retrieve all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = [
            [
                'label' => __('Disabled'),
                'value' => self::STATUS_DISABLED
            ],
            [
                'label' => __('Enabled (Splash Page Template)'),
                'value' => self::STATUS_ENABLED_SPLASH
            ],
            [
                'label' => __('Enabled (Magento Login Page Template)'),
                'value' => self::STATUS_ENABLED_DEFAULT
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
