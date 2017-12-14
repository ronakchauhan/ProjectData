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

class Init extends \Magento\Framework\View\Element\Template
{
	/**
	 * Retrieve preview date if it exists
	 * @return string
	 */
	public function getPreviewDate()
	{
		if ($this->_request->getParam('previewDate')) {
			return $this->_request->getParam('previewDate');
		}

		return '';
	}

	/**
	 * Is previe mode enabled
	 * @return boolean
	 */
	public function getPreviewMode()
	{
		return $this->_request->getParam('psPreviewMode');
	}
}
