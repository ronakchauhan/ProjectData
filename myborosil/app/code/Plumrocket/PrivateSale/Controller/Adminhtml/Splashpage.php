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

namespace Plumrocket\PrivateSale\Controller\Adminhtml;

class Splashpage extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Plumrocket_PrivateSale::privatesale';

    /**
     * Image
     * @var \Plumrocket\PrivateSale\Model\Image
     */
    protected $image;

    /**
     * Config
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $config;

    /**
     * Json helper
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * App config
     * @var \Magento\Framework\App\Config\ReinitableConfigInterface
     */
    protected $appConfig;

    /**
     * Splashpage
     * @var \Plumrocket\PrivateSale\Model\Splashpage
     */
    protected $splashpage;

    /**
     * Registry
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * Constructor
     * @param \Plumrocket\PrivateSale\Model\Image                     $image
     * @param \Plumrocket\PrivateSale\Model\Splashpage                $splashpage
     * @param \Magento\Config\Model\ResourceModel\Config              $config
     * @param \Magento\Backend\App\Action\Context                     $context
     * @param \Magento\Framework\Json\Helper\Data                     $jsonHelper
     * @param \Magento\Framework\App\Config\ReinitableConfigInterface $appConfig
     * @param \Magento\Framework\Registry                             $coreRegistry
     */
 	public function __construct(
 		\Plumrocket\PrivateSale\Model\Image $image,
 		\Plumrocket\PrivateSale\Model\Splashpage $splashpage,
 		\Magento\Config\Model\ResourceModel\Config $config,
 		\Magento\Backend\App\Action\Context $context,
 		\Magento\Framework\Json\Helper\Data $jsonHelper,
 		\Magento\Framework\App\Config\ReinitableConfigInterface $appConfig,
 		\Magento\Framework\Registry $coreRegistry
 	) {

 		parent::__construct($context);
 		$this->coreRegistry = $coreRegistry;
 		$this->image = $image;
 		$this->config = $config;
 		$this->appConfig = $appConfig;
 		$this->jsonHelper = $jsonHelper;
 		$this->splashpage = $splashpage;
 	}

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
    }
}
