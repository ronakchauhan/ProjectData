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

/* Uninstall Private Sales Module */
class Uninstall extends \Plumrocket\Base\Setup\AbstractUninstall
{
    /**
     * Config section id
     * @var string
     */
    protected $_configSectionId = 'privatesale';

    /**
     * Pathes to files
     * @var Array
     */
    protected $_pathes = ['/app/code/Plumrocket/PrivateSale'];

    /**
     * Attributes
     * @var Array
     */
    protected $_attributes = [
        \Magento\Catalog\Model\Category::ENTITY => [
                'privatesale_email_image',
                'privatesale_date_start',
                'privatesale_date_end',
                'privatesale_before_event_start',
                'privatesale_event_end',
            ],
        \Magento\Catalog\Model\Product::ENTITY  =>  [
                'privatesale_date_start',
                'privatesale_date_end',
                'privatesale_before_event_start',
                'privatesale_event_end',
        ]
    ];

    /**
     * Tables
     * @var Array
     */
    protected $_tables = [
        'plumrocket_privatesale_product_indexer',
        'plumrocket_privatesale_preview_access',
        'plumrocket_privatesale_images',
        'plumrocket_privatesale_emailtemplates'
    ];
}
