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


namespace Plumrocket\PrivateSale\Observer\Event;

class CategoryFlatLoadnodesBefore extends EventObserver
{
    /**
     * {@inheritdoc}
     */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		try {
			if ($this->helper->moduleEnabled()) {
				$select = $observer->getEvent()->getSelect();
				$select->where(
					"privatesale_date_start < ? OR privatesale_date_start IS NULL AND privatesale_date_start > ? OR privatesale_date_start IS NULL",
					$this->event->getCurrentDate(false)
				);
			}
		} catch (\Exception $e) {}
	}
}
