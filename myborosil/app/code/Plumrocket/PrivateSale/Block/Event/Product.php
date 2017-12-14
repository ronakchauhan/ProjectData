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

namespace Plumrocket\PrivateSale\Block\Event;

class Product extends AbstractEvent
{

	/**
	 * Categories
	 * @var Array
	 */
	protected $_categories = array();

	/**
	 * Retrieve current Product
	 * @return \Magento\Catalog\Model\Product
	 */
	protected function _getProduct()
	{
		if ($product = $this->getProduct()) {
			if (is_int($product)) {
				$product = $this->productFactory->create()->load($product);
			}

			return $product;
		}
		return $this->registry->registry('current_product');
	}

	/**
	 * Retrieve category for product
	 * @return \Magento\Catalog\Model\Category
	 */
	protected function _getCategory()
	{
		if ($this->registry->registry('current_category')) {
			return $this->registry->registry('current_category');
		}

		$product = $this->_getProduct();
		$event = $this->_getProductEvent();

		if ($event->getCategoryId()) {
			$category = $this->categoryFactory->create()->load($event->getCategoryId());
		} else {
			$category = $product
				->getCategoryCollection()
				->addAttributeToSelect('privatesale_display_type')
				->setPageSize(1)
				->getFirstItem();
		}

		return $category;
	}

	/**
	 * Retrieve product event
	 * @param  \Magento\Catalog\Model\Product $product
	 * @return \Plumrocket\Privatesales\Model\Indexer
	 */
	protected function _getProductEvent()
	{
		$product = $this->_getProduct();
		if (!$product->getPrivatesaleEvent()) {
			$event = $this->event->getActiveProductEvent($product);
			$product->setPrivatesaleEvent($event);
		}

		return $product->getPrivatesaleEvent();
	}

	/**
	 * Retrieve product id
	 * @return int
	 */
	public function getItemId()
	{
		return $this->_getProduct()->getId();
	}

	/**
	 * Retrieve event end time
	 * @return int
	 */
	public function getEventEnd()
	{
		$event = $this->_getProductEvent();
		if (!$event) {
			return '';
		}

		if (strtotime($event->getData('start_date')) > $this->event->getCurrentDate()) {
			return '';
		}

		return $event->getData('end_date') ? strtotime($event->getData('end_date')) : 0;
	}

	/**
	 * Retrieve event start date
	 * @return string
	 */
	public function getEventStart()
	{
		return strtotime($this->_getProductEvent()->getStartDate());
	}
}
