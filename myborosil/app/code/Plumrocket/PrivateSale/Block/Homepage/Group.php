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

class Group extends AbstractHomepage
{
	/**
	 * {@inheritdoc}
	 */
	public function getCollection()
	{
		$category = $this->getCategory();
		$type = 'groups';
		if ($this->_events[$type][$category->getId()] == null) {
			$_categories = $this->_getChildrenCategories($category);
			if (count($_categories)) {
				foreach ($_categories as $cat) {
					$childs = $this->_getChildrenCategories($cat, $type);
					$childs = $this->_addStartDateFilter($childs);
					$this->_events[$type][$category->getId()][$cat->getId()] = [
						'name' => $cat->getName(),
						'items' => $this->_addEndDateFilter($childs)
					];
					$this->addEventsCount($childs->count());
				}
			}
		}

		return $this->_events[$type][$category->getId()];
	}
}
