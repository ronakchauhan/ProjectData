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

class AddPreviewPanel implements ObserverInterface
{
	/**
	 * Helper
	 * @var \Plumrocket\PrivateSale\Helper\Preview
	 */
	protected $previewHelper;

	/**
	 * Constructor
	 * @param \Plumrocket\PrivateSale\Helper\Preview $previewHelper
	 */
	public function __construct(
		\Plumrocket\PrivateSale\Helper\Preview $previewHelper
	) {
		$this->previewHelper = $previewHelper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        if ($this->previewHelper->isAllowPreview()) {
            $layout = $observer->getLayout();
            $layout->getUpdate()->addHandle('plumrocket_privatesale_preview_panel');
        }
	}
}
