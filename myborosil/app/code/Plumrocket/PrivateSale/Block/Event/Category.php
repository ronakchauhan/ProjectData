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

class Category extends AbstractEvent
{

	/**
	 * Retrieve current category
	 * @return \Magento\Catalog\Model\Category
	 */
	protected function _getCategory()
	{
		if ($category = $this->getCategory()) {
			if (is_int($category)) {
				$category = $this->categoryFactory->create()->load($category);
			}

			return $category;
		}
		return $this->registry->registry('current_category');
	}

	/**
	 * Retrieve product id
	 * @return int
	 */
	public function getItemId()
	{
		return $this->_getCategory()->getId();
	}

	/**
	 * Retrieve event end time
	 * @return int
	 */
	public function getEventEnd()
	{
		return strtotime($this->_getCategory()->getData('privatesale_date_end'));
	}
}
