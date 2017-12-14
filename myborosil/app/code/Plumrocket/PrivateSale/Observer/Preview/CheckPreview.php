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

namespace Plumrocket\PrivateSale\Observer\Preview;

use Magento\Framework\Event\ObserverInterface;

class CheckPreview implements ObserverInterface
{

	/**
	 * Preview helper
	 * @var Plumrocket\PrivateSale\Helper\Preview
	 */
	protected $previewHelper;

	/**
	 * Request
	 * @var \Magento\Framework\App\Request\Http
	 */
	protected $request;

	/**
	 * Registry
	 * @var \Magento\Framework\Registry
	 */
	protected $registry;

	/**
	 * Store manager
	 * @var  \Magento\Store\Model\StoreManager
	 */
	protected $storeManager;

	/**
	 * Constructor
	 * @param \Magento\Framework\App\Request\Http    $request
	 * @param \Magento\Framework\Registry            $registry
	 * @param \Magento\Store\Model\StoreManager      $storeManager
	 * @param \Plumrocket\PrivateSale\Helper\Preview $previewHelper
	 */
	public function __construct(
		\Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManager $storeManager,
		\Plumrocket\PrivateSale\Helper\Preview $previewHelper
	) {
		$this->previewHelper = $previewHelper;
		$this->request = $request;
		$this->storeManager = $storeManager;
		$this->registry = $registry;
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        $previewKey = $this->previewHelper->getPreviewKey();

        if ($this->previewHelper->isAllowPreview()) {
            $store = $this->request->getParam('store');
            if ($store) {
                $store = $this->storeManager->getStore($store);
                $this->storeManager->setCurrentStore($store);
            }
            $this->registry->register('privatesales_preview_mode', true, true);
        }
	}
}
