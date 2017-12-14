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

class EventLanding extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    const DISPLAY_LOGIN_PAGE = 'login';
    const DISPLAY_REGISTER_PAGE = 'register';

    /**
     * Page factory
     * @var Magento\Cms\Model\PageFactory
     */
    protected $pageFactory;

    /**
     * Constructor
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     */
    public function __construct(
    	\Magento\Cms\Model\PageFactory $pageFactory
    ) {
    	$this->pageFactory = $pageFactory;
    }

    /**
     * Retrieve all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = [
            [
                'label' => __('Login Page'),
                'value' => self::DISPLAY_LOGIN_PAGE
            ],
            [
                'label' => __('Registration Page'),
                'value' => self::DISPLAY_REGISTER_PAGE
            ],
            [
                'label' => '------------------------------',
                'value' => []
            ]
        ];

        $cmsPages = $this->pageFactory->create()->getCollection();
        foreach ($cmsPages as $page) {
        	$options[] = [
        		'label' => '#' . $page->getId() . ' - ' .$page->getTitle(),
        		'value'	=> $page->getId()
        	];
        }

        return $options;
    }
}
