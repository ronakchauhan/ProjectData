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

class AbstractHomepage extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{

	/**
	 * Events
	 * @var array
	 */
	protected $_events;

	/**
	 * Default event item block
	 * @var string
	 */
	protected $_defaultEventItemBlock = 'homepage.event.item';

	/**
	 * Block title
	 * @var string
	 */
	protected $_blockTitle = '';

	/**
	 * Category Factory
	 * @var \Magento\Catalog\Model\Category
	 */
	protected $categoryFactory;

	/**
	 * Store manager
	 * @var \Magento\Store\Model\StoreManager
	 */
	protected $storeManager;

	/**
	 * Registry
	 * @var \Magento\Framework\Registry
	 */
	protected $registry;

	/**
	 * Data Helper
	 * @var \Plumrocket\PrivateSale\Helper\Data
	 */
	protected $helper;

	/**
	 * Event model
	 * @var \Plumrocket\PrivateSale\Model\Event
	 */
	protected $event;

	/**
	 * Current category
	 * @var object
	 */
	protected $_currentCategory;

	/**
	 * Constructor
	 * @param \Magento\Catalog\Model\CategoryFactory           $categoryFactory
	 * @param \Magento\Framework\Registry                      $registry
	 * @param \Magento\Store\Model\StoreManager                $storeManager
	 * @param \Plumrocket\PrivateSale\Helper\Data                $dataHelper
	 * @param \Plumrocket\PrivateSale\Model\Event                $event
	 * @param \Magento\Framework\View\Element\Template\Context $context
	 * @param array                                            $data
	 */
	public function __construct(
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManager $storeManager,
        \Plumrocket\PrivateSale\Helper\Data $dataHelper,
        \Plumrocket\PrivateSale\Model\Event $event,
        \Magento\Framework\View\Element\Template\Context $context,
        $data = []
	) {
		$this->categoryFactory = $categoryFactory;
		$this->helper = $dataHelper;
		$this->registry = $registry;
		$this->event = $event;
		$this->storeManager = $storeManager;
		parent::__construct($context, $data);
	}

	/**
	 * Retrieve collection
	 * @return \Magento\Catalog\Model\Resource\Category\Collection
	 */
	public function getCollection()
	{
		return $this->_events;
	}

	/**
	 * Retrieve category
	 * @return \Magento\Catalog\Model\Category
	 */
	public function getCategory()
	{
		if ($this->_currentCategory === null ) {
			if ($this->getCategoryId()) {
				$this->_currentCategory = $this->categoryFactory->create()->load($this->getCategoryId());
			} elseif ($this->registry->registry('current_category')) {
				$this->_currentCategory = $this->registry->registry('current_category');
			} else {
				$catId = $this->storeManager->getStore()->getRootCategoryId();
				$this->_currentCategory = $this->categoryFactory->create()->load($catId);
			}
		}

		return $this->_currentCategory;
	}

	/**
	 * Retrieve children categories
	 * @param  \Magento\Catalog\Model\Category $category
	 * @return Collection
	 */
	protected function _getChildrenCategories($category, $type = 'events')
	{
		$_categories = $this->helper->getChildrenCategories($category, $type);
		return $_categories;
	}

	/**
	 * Retrieve current time
	 * @return int
	 */
	protected function _getCurrentTime()
	{
		return $this->event->getCurrentDate();
	}

	/**
	 * Retrieve current date
	 * @return string
	 */
	protected function _getCurrentDate()
	{
		return $this->event->getCurrentDate(false);
	}

	/**
	 * Retrieve item html
	 * @param  \Magento\Catalog\Model\Category  $item
	 * @return string
	 */
	public function getItemHtml($item, $comingSoon = false)
	{

		$block = $this->getLayout()
			->getBlock($this->_getEventItemBlock());

		if (!$block) {
			return '';
		}

		return $block
			->setComingSoon($comingSoon)
			->setItem($item)
			->toHtml();
	}

	/**
	 * Retrieve item block
	 * @return string
	 */
	protected function _getEventItemBlock()
	{
		if ($this->getEventItemBlock()) {
			return $this->getEventItemBlock();
		}

		return $this->_defaultEventItemBlock;
	}

	/**
	 * Add end date filter
	 * @param \Magento\Catalog\Model\Resource\Category\Collection $collection
	 * @param string $currentDate
	 */
	protected function _addEndDateFilter($collection, $currentDate = null)
	{
		if ($currentDate == null) {
			$currentTime = $this->_getCurrentTime();
			if ($this->getEndingSoonDays()) {
				//If enbled ending soon block, then add to current time days
				$currentTime += $this->getEndingSoonDays() * 24 * 60 * 60;
			}

			$currentDate = date('Y-m-d H:i:s', $currentTime);
		}

		$this->helper->addEndDateFilter($collection, $currentTime);

        return $collection;
	}

	/**
	 * Add start date filter
	 * @param \Magento\Catalog\Model\Resource\Category\Collection $collection
	 * @param string $startDate
	 */
	protected function _addStartDateFilter($collection, $startDate = null)
	{
		if ($startDate == null) {
			$startDate = $this->_getCurrentDate();
		}

		$collection
			->addAttributeToFilter('privatesale_date_start',
                [
                    ['lteq' => $startDate]
                ]
            );

        return $collection;
	}

	/**
	 * Block title setter
	 * @param string $title
	 */
	public function setBlockTitle($title = '')
	{
		$this->_blockTitle = __($title);
		return $this;
	}

	/**
	 * Retrieve block title
	 * @return string
	 */
	public function getBlockTitle()
	{
		return $this->_blockTitle;
	}

	/**
	 * Retrieve coming soon days from parent block
	 * @return int
	 */
	public function getComingSoonDays()
	{
		if ($this->getData('coming_soon_days')) {
			return $this->getData('coming_soon_days');
		}
		if ($parent = $this->getParentBlock()) {
			return $parent->getComingSoonDays();
		}
		return null;
	}

	/**
	 * Retrieve ending soon days from parent block or from data
	 * @return int
	 */
	public function getEndingSoonDays()
	{
		if ($this->getData('ending_soon_days')) {
			return $this->getData('ending_soon_days');
		}
		if ($parent = $this->getParentBlock()) {
			return $parent->getEndingSoonDays();
		}

		return null;
	}

	/**
	 * Add events count
	 * @param int $count
	 */
	public function addEventsCount($count)
	{
		$parent = $this->getParentBlock();
		if ($parent && $parent instanceof \Plumrocket\PrivateSale\Block\Homepage) {
			$parent->addEventsCount($count);
		}
		return $this;
	}
}
