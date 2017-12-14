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

class Comingsoon extends AbstractHomepage
{

	/**
	 * {@inheritdoc}
	 */
	public function getCollection()
	{
		$type = 'coming_soon';
		$category = $this->getCategory();
		if ($this->_events[$type][$category->getId()] == null) {
			$_categories = $this->_getChildrenCategories($category, 'coming_soon');

			$currentDate = $this->_getCurrentDate();
			$startTime = $this->_getCurrentTime() + ($this->getComingSoonDays() * 24 * 60 * 60);
			$startDate = date('Y-m-d H:i:s', $startTime);

			$_categories = $_categories
				->addAttributeToFilter(
					'privatesale_date_start',
					['lteq' => $startDate]
				)->addAttributeToFilter(
					'privatesale_date_start',
					['gteq' => $currentDate]
				);

			$this->_events[$type][$category->getId()] = $_categories;
			$this->addEventsCount($_categories->count());
		}

		return $this->_events[$type][$category->getId()];
	}

}
