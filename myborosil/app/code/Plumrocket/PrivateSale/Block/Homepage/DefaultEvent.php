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

namespace Plumrocket\PrivateSale\Block\Homepage;

class DefaultEvent extends AbstractHomepage
{
	/**
	 * Retrieve colletion of categories
	 * @return Magento\Catalog\Model\ResourceModel\Category\Collection
	 */
	public function getCollection()
	{
		$category = $this->getCategory();
		$type = 'event';
		if ($this->_events[$type][$category->getId()] == null) {
			$_categories = $this->_getChildrenCategories($category);

			$this->_addStartDateFilter($_categories);
			$this->_events[$type][$category->getId()] = $this->_addEndDateFilter($_categories);
			$this->addEventsCount($_categories->count());
		}

		return $this->_events[$type][$category->getId()];
	}

}
