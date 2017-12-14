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

class PrivateEvent extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

	const USE_PARENT = 0;
    const PRIVATE_YES = 2;
    const PRIVATE_NO = 1;

    /**
     * {@inheritdoc}
     */
    public function getAllOptions()
    {
        $options = [
            [
                'label' => __('Yes'),
                'value' => self::PRIVATE_YES
            ],
            [
                'label' => __('No'),
                'value' => self::PRIVATE_NO
            ]
        ];

        return $options;
    }
}
