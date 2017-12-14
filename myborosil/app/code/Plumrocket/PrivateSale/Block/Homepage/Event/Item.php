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

namespace Plumrocket\PrivateSale\Block\Homepage\Event;

class Item extends \Magento\Framework\View\Element\Template
{
	/**
	 * Customer session
	 * @var Magento\Customer\Model\Session
	 */
	protected $privateEvent;

	/**
	 * @param \Magento\Framework\View\Element\Template\Context $context
	 * @param \Magento\Customer\Model\Session $session
	 * @param array $data
	 */
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Plumrocket\PrivateSale\Model\PrivateEvent $privateEvent,
		array $data = []
	) {
        parent::__construct($context, $data);
        $this->privateEvent = $privateEvent;
    }

	/**
	 * Is countdown must be shown
	 * @param  \Magento\Catalog\Model\Category $item
	 * @return boolean
	 */
	public function showCountdown($item)
	{
		return true;
	}

	/**
	 * Retrieve event image
	 * @param  Magento\Catalog\Model\Category $item
	 * @return string
	 */
	public function getEventImage($item)
	{
		$url = '';

		if ($item->getPrivatesaleEventImage()) {
			$url = $this->_storeManager->getStore()->getBaseUrl(
	            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
	        ) . 'catalog/category/' . $item->getPrivatesaleEventImage();
		} else {
			$url = $this->getViewFileUrl('Plumrocket_PrivateSale::images/default.jpg');
		}

		return $url;
	}

	/**
	 * Retrieve tru if event is private sale and customer is not loged in
	 * @param  Magento\Catalog\Model\Category $item
	 * @return boolean
	 */
	public function isEventLocked($item)
	{
		return $this->privateEvent->isCategoryLocked($item);
	}
}
