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

namespace Plumrocket\PrivateSale\Block;

class Homepage extends \Magento\Framework\View\Element\Template
{
	/**
	 * Coming soon days
	 * @var integer
	 */
	protected $_comingSoonDays = 3;

	/**
	 * Ending soon days
	 * @var int
	 */
	protected $_endingSoonDays = false;

	/**
	 * Events count
	 * @var integer
	 */
	protected $_eventsCount = 0;

	/**
	 * Coming soon days setter
	 * @param int $days
	 */
	public function setComingSoonDays($days)
	{
		$this->_comingSoonDays = $days;
		return $this;
	}

	/**
	 * Retrieve coming soon days
	 * @return int;
	 */
	public function getComingSoonDays()
	{
		return $this->_comingSoonDays;
	}

	/**
	 * Retrieve ending soon days
	 * @return int
	 */
	public function getEndingSoonDays()
	{
		return $this->_endingSoonDays;
	}

	/**
	 * Ending soon days setter
	 * @param int $days
	 */
	public function setEndingSoonDays($days)
	{
		$this->_endingSoonDays = $days;
		return $this;
	}

	/**
	 * Add events count
	 * @param integer $count
	 */
	public function addEventsCount($count = 0)
	{
		$this->_eventsCount += $count;
		return $this;
	}

	/**
	 * Retrieve eventes count
	 * @return int
	 */
	public function getEventsCount()
	{
		return $this->_eventsCount;
	}
}
