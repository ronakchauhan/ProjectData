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

class AbstractEvent extends \Magento\Framework\View\Element\Template
{
	/**
	 * Category factory
	 * @var \Magento\Catalog\Model\CategoryFactory
	 */
	protected $categoryFactory;

	/**
	 * Helper
	 * @var Plumrocket\PrivateSale\Helper\Data
	 */
	protected $helper;

	/**
	 * Registry
	 * @var Magento\Framework\Registry
	 */
	protected $registry;

	/**
	 * Event
	 * @var Plumrocket\PrivateSale\Model\Event
	 */
	protected $event;

	/**
	 * Constructo
	 * @param \Magento\Catalog\Model\CategoryFactory           $categoryFactory
	 * @param \Magento\Framework\Registry                      $registry
	 * @param \Plumrocket\PrivateSale\Model\Event              $event
	 * @param \Plumrocket\PrivateSale\Helper\Data              $dataHelper
	 * @param \Magento\Framework\View\Element\Template\Context $context
	 * @param array                                            $data
	 */
	public function __construct(
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Registry $registry,
        \Plumrocket\PrivateSale\Model\Event $event,
        \Plumrocket\PrivateSale\Helper\Data $dataHelper,
        \Magento\Framework\View\Element\Template\Context $context,
        $data = []
	) {
		$this->categoryFactory = $categoryFactory;
		$this->helper = $dataHelper;
		$this->event = $event;
		$this->registry = $registry;
		parent::__construct($context, $data);
	}

	/**
	 * Display countdown
	 * This option currentlu retrieve only true, but in future it can be changed and added logic
	 * @return boolean
	 */
	public function displayCountdown()
	{
		return true;
	}

	/**
	 * Element id
	 * @var string
	 */
	protected $_elementId = '';

	/**
	 * Set element id
	 * @param string $elementId
	 */
	public function setElementId($elementId)
	{
		$this->_elementId = $elementId;
	}

	/**
	 * Retrieve element id
	 * @return string
	 */
	public function getElementId()
	{
		return $this->_elementId;
	}
}
