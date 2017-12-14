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

namespace Plumrocket\PrivateSale\Model;

class Event extends \Magento\Framework\Model\AbstractModel
{
	const DM_HOMEPAGE = 'HOMEPAGE';

	const CONFIG_END_ACTION = 'end';
	const CONFIG_START_ACTION = 'start';
	/**
	 * Categories
	 * @var Array
	 */
	protected $_categories;

	/**
	 * Events
	 * @var array
	 */
	protected $_events;

	/**
	 * Active product events
	 * @var array
	 */
	protected $_productEvent = array();

	/**
	 * Category factory
	 * @var \Magento\Catalog\Model\CategoryFactory
	 */
	protected $categoryFactory;

	/**
	 * Helper
	 * @var \Plumrocket\PrivateSale\Helper\Data
	 */
	protected $helper;

	/**
	 * Product collection
	 * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
	 */
	protected $productCollection;

	/**
	 * Date
	 * @var \Magento\Framework\Stdlib\DateTime\DateTime
	 */
	protected $date;

	/**
	 * Preview helper
	 * @var Plumrocket\PrivateSale\Helper\Preview
	 */
	protected $previewHelper;

	/**
	 * Product factory
	 * @var \Magento\Catalog\Model\ProductFactory
	 */
	protected $productFactory;

	/**
	 * Indexer
	 * @var \Plumrocket\PrivateSale\Model\IndexerFactory
	 */
	protected $indexerFactory;

	/**
	 * Store manager
	 * @var \Magento\Store\Model\StoreManager
	 */
	protected $storeManager;

	/**
	 * Category product
	 * @var array
	 */
	protected $categoryProducts = [];


	/**
	 * Constructor
	 * @param \Magento\Catalog\Model\CategoryFactory                  $categoryFactory
	 * @param \Magento\Framework\Model\Context                        $context
	 * @param \Magento\Framework\Registry                             $registry
	 * @param \Plumrocket\PrivateSale\Helper\Data                     $helper
	 * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection
	 * @param \Magento\Framework\Stdlib\DateTime\DateTime             $date
	 * @param \Plumrocket\PrivateSale\Helper\Preview                  $previewHelper
	 * @param \Magento\Catalog\Model\ProductFactory                   $productFactory
	 * @param \Magento\Store\Model\StoreManager                       $storeManager
	 * @param \Plumrocket\PrivateSale\Model\IndexerFactory            $indexerFactory
	 * @param array                                                   $data
	 */
    public function __construct(
    	\Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
		\Plumrocket\PrivateSale\Helper\Data $helper,
		\Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
		\Magento\Framework\Stdlib\DateTime\DateTime $date,
		\Plumrocket\PrivateSale\Helper\Preview $previewHelper,
		\Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Store\Model\StoreManager $storeManager,
		\Plumrocket\PrivateSale\Model\IndexerFactory $indexerFactory,
        array $data = []
    ) {
    	$this->categoryFactory = $categoryFactory;
    	parent::__construct(
    		$context,
    		$registry,
    		null,
    		null,
    		$data
    	);
		$this->helper = $helper;
		$this->productCollection = $productCollection;
		$this->date = $date;
		$this->previewHelper = $previewHelper;
		$this->productFactory = $productFactory;
		$this->indexerFactory = $indexerFactory;
		$this->storeManager = $storeManager;
	}

	/**
	 * Does products must be displayed before event start
	 * @param  Magento\Catalog\Model\Product $product
	 * @return boolean
	 */
	public function disableProductBeforeEventStart($product)
	{
        //If product has date start and it dont enabled and option Display Product Before Event Starts seted to "No"
        //In this case disable product and remember it in privatesale database
        $currentTime = $this->getCurrentDate();
        $startTime = strtotime($product->getPrivatesaleDateStart());

        if ($startTime > $currentTime
        	&& $product->getStatus() != \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED
        	&& $product->getPrivatesaleStatus() == 1
        	&& $this->getProductBeforeEventStart($product) == \Plumrocket\PrivateSale\Model\Config\Source\Beforeeventstart::DISPLAY_NO
        ) {
        	return true;
        }

		return false;
	}

	/**
	 * Retrieve before events start value for preset product
	 * @param  \Magento\Catalog\Model\Product $product
	 * @return int
	 */
	public function getProductBeforeEventStart($product, $category = null)
	{
		$beforeEventStart = (int)$product->getPrivatesaleBeforeEventStart();
		//If option eq "Use category". In this case load all parent categries and try to find event and geting option from it.
		if (!$beforeEventStart || $beforeEventStart == \Plumrocket\PrivateSale\Model\Attribute\Source\Product\Beforeeventstart::USE_CATEGORY) {

			if ($category === null) {
				$category = $this->getProductEventCategory($product);
			}

			$beforeEventStart = $this->getCategoryBeforeEventStart($category);
		}

		//If before event start value still equal "Use category", it means, that no one parent category hasn't event
		//In this case retrieve this options from configuration
		if ($beforeEventStart == \Plumrocket\PrivateSale\Model\Attribute\Source\Product\Beforeeventstart::USE_CATEGORY) {
			$beforeEventStart = (int)$this->getConfig(\Plumrocket\PrivateSale\Model\Config\Source\Beforeeventstart::CONFIG_PATH);
		}


		return (int)$beforeEventStart;
	}

	/**
	 * Retrieve product category
	 * @param  Magento\Catalog\Model\Product $product
	 * @return Magento\Catalog\Model\Category
	 */
	public function getProductEventCategory($product)
	{
		//Getting event with biggest date end;
		$event = $this->getEventByProduct($product);
		//If such event exists and category id not zero, then load this category
		if ($event && $event->getId() && $event->getCategoryId() != 0) {
			if ($this->_registry->registry('current_category') && $this->_registry->registry('current_category')->getId() == $event->getId()) {
				return $this->_registry->registry('current_category');
			}

			return $this->categoryFactory->create()->load($event->getCategoryId());
		} else {
			if ($this->_registry->registry('current_category')) {
				return $this->_registry->registry('current_category');
			}

			$categories = $this->_getCategoriesByProduct($product);
			foreach ($categories as $cat) {
				if ($cat->getPrivatesaleDateStart()) {
					return $cat;
				}
			}
		}

		return false;
	}

	/**
	 * Retrieve before event start option for category
	 * @param  Magento\Catalog\Model\Category $category
	 * @return int
	 */
	public function getCategoryBeforeEventStart($category)
	{
		if (!$category) {
			$beforeEventStart = (int)$this->_getConfig(self::CONFIG_START_ACTION);
		} else {
			$beforeEventStart = (int)$category->getPrivatesaleBeforeEventStart();
			if ($beforeEventStart == \Plumrocket\PrivateSale\Model\Attribute\Source\Category\Beforeeventstart::USE_CONFIG) {
				$beforeEventStart = $this->_getConfig(self::CONFIG_START_ACTION);
			}
		}

		return (int)$beforeEventStart;
	}


	/**
	 * Retrieve category products
	 * @param  \Magento\Catalog\Model\Category $category
	 * @return Collection
	 */
	public function getCategoryProducts(\Magento\Catalog\Model\Category $category)
	{
		if (!isset($this->categoryProducts[$category->getId()])) {
			$this->categoryProducts[$category->getId()] = $this->productCollection
	            ->addCategoryFilter($category)
	            ->addAttributeToSelect(
	            	[
	            		'privatesale_private_event',
	            		'privatesale_private_event_flag',
		            	'privatesale_event_end',
		            	'privatesale_before_event_start',
		            	'privatesale_date_end',
		            	'privatesale_date_start',
		            	'privatesale_status'
		            ]
		        );
	    }

	    return $this->categoryProducts[$category->getId()];
	}

	/**
	 * Retrieve event end action
	 * @param  \Magento\Catalog\Model\Category $category
	 * @param  \Magento\Catalog\Model\Product $product
	 * @return int
	 */
	protected function _getEventEndAction($category, $product = null)
	{
		$useCategory = false;
		if ($product !== null) {
			$eventEnd = $product->getData('privatesale_event_end');

			if ($eventEnd == \Plumrocket\PrivateSale\Model\Attribute\Source\Product\Eventend::USE_CATEGORY || $eventEnd === null) {
				$useCategory = true;
			} else {
				return (int)$eventEnd;
			}
		}

		if ($product === null || $useCategory) {

			if (!$category) {
				$category = $this->getProductEventCategory($product);
				if (!$category) {
					return $this->_getConfig(self::CONFIG_END_ACTION);
				}
			}

			if (!$category->getData('privatesale_date_end')) {
				return \Plumrocket\PrivateSale\Model\Config\Source\Eventend::DO_NOTHING;
			}

			$eventEnd = $category->getData('privatesale_event_end');
			if ($eventEnd == \Plumrocket\PrivateSale\Model\Attribute\Source\Category\Eventend::USE_CONFIG) {
				$eventEnd = $this->_getConfig(self::CONFIG_END_ACTION);
			}
		}

		return (int)$eventEnd;
	}

	/**
	 * Public function for retrieve event end action
	 * @param  \Magento\Catalog\Model\Category $category
	 * @param  \Magento\Catalog\Model\Product $product
	 * @return int
	 */
	public function getEventEndAction($category = null, $product = null)
	{
		return $this->_getEventEndAction($category, $product);
	}

	/**
	 * Retrieve current time or date for core/date
	 * Also this method work with preview date
	 * @param  boolean $timestamp
	 * @return string | int
	 */
	public function getCurrentDate($timestamp = true)
	{
		if ($this->_registry->registry('privatesales_preview_mode')) {

			$currentSeconds = $this->date->gmtDate('H') * 60 * 60 + ( $this->date->gmtDate('i') + 2) * 60;

			$previewTime = strtotime(date("Y-m-d", $this->previewHelper->getPreviewTime())) + $currentSeconds;
			if ($timestamp) {
				return $previewTime;
			}

			$date = date("Y-m-d H:i:s", $previewTime);
			return $date;
		}

		if ($timestamp) {
			return $this->date->timestamp();
		}

		return $this->date->date();
	}

	/**
	 * Is event active
	 * @return boolean
	 */
	public function isEventActive($category)
	{
		if ($category->getPrivatesaleDateStart()) {
			return true;
		}

		return false;
	}

	/**
	 * Check category event
	 * @param  \Magento\Catalog\Model\Category $category
	 * @return boolean
	 */
    public function checkCategory($category, $isCollection = false)
    {
        if (!$category || !$category->getId()) {
            return false;
        }

        $currentTime = $this->getCurrentDate();
        if ($category->getPrivatesaleDateStart()) {
            $startTime = $this->toTime($category->getPrivatesaleDateStart());

            if ($category->getPrivatesaleDateEnd()) {
            	$endTime = $this->toTime($category->getPrivatesaleDateEnd());
            } else {
            	$endTime = 0;
            }
            if ($startTime > $currentTime) {
            	return false;
            } elseif ($endTime > 0 && $currentTime  > $endTime) {
            	$endAction = $this->getEventEndAction($category);

            	if ($endAction == \Plumrocket\PrivateSale\Model\Config\Source\Eventend::NOT_FOUND) {
            		return false;
            	} elseif ($endAction == \Plumrocket\PrivateSale\Model\Config\Source\Eventend::DISABLE_ADD_TO_CART && $isCollection) {
            		return false;
            	}
            }
        }

        return true;
    }

    /**
     * Retrieve action for product
     * @param  \Magento\Catalog\Model\Product $product
     * @return int
     */
    public function checkProduct($product)
    {
    	$action = \Plumrocket\PrivateSale\Model\Config\Source\Eventend::DO_NOTHING;
    	if (!$product || !$product->getId()) {
    		return $action;
    	}

    	$productEvents = $this->getEventsByProduct($product);

    	if (!$productEvents->getSize()) {
    		return $action;
    	}

    	$event = $this->getActiveProductEvent($product);

    	$product->setPrivatesaleEvent($event);


    	if ($event === null) {
    		return $action;
    	}

    	$action = $this->_getEventAction($event);

    	if ($action === false) {
    		return \Plumrocket\PrivateSale\Model\Config\Source\Eventend::DO_NOTHING;
    	}

    	return $action;
    }

    /**
     * Retrieve only one event for product
     * @param  \Magento\Catalog\Model\Product $product
     * @return Plumrocket_Privatesale_Model_Indexer
     */
    public function getActiveProductEvent($product)
    {
    	if (!isset($this->_productEvent[$product->getId()])) {

    		$productEvents = $this->getEventsByProduct($product);

	    	$flag = false;
    		$currentTime = $this->getCurrentDate();

	    	foreach ($productEvents as $event) {
	    		$start = $this->toTime($event->getStartDate());
	    		$end = $this->toTime($event->getEndDate());
	    		if ($start <= $currentTime && $end >= $currentTime) {
	    			//In this case product belong to active event, and it must be active
	    			$this->_productEvent[$product->getId()] = $event;
	    			$flag = true;
	    		}

	    		/**
	    		 * Event with category id 0 most priority
	    		 * This logic is not ideal, thats why it commented
	    		 */
	    		/*if ($event->getCategoryId() == 0) {
	    			return $event;
	    		}*/

	    		if (!$event->getEndDate()) {
	    			return $event;
	    		}

	    		if (!$flag) {
    				$this->_productEvent[$product->getId()] = $event;
    				$flag = true;
		    	}
	    	}
    	}

    	if (!isset($this->_productEvent[$product->getId()])) {
    		return false;
    	}

    	return $this->_productEvent[$product->getId()];
    }

    /**
     * Retrieve all events by product
     * @param  mixed $product
     * @return Plumrocket_Privatesales_Model_Indexer
     */
    public function getEventsByProduct($product)
    {

    	if (is_int($product)) {
    		$product = $this->productFactory->create()->load($product);
    	}

    	if (!$product->getId()) {
    		throw new \Exception('Product not found');
    	}

    	if (!isset($this->_events[$product->getId()])) {
    		$productEvents = $this->indexerFactory->create()->getCollection()
    			->addFieldToFilter('product_id', $product->getId())
    			->addFieldToFilter('store_id',
    				[
    					['eq'	=>	$this->getStoreId()],
    					['eq'	=> 0]
    				]
    			);
    		$productEvents->getSelect()->order('end_date DESC');
    		$this->_events[$product->getId()] = $productEvents;
    	}

    	return $this->_events[$product->getId()];
    }

	/**
	 * Retrieve product categories
	 * @param  \Magento\Catalog\Model\Product $product
	 * @return \Magento\Catalog\Mode\Resource\Category\Collection
	 */
	protected function _getCategoriesByProduct($product)
	{
		$cats = $product->getCategoryIds();
		if (empty($cats)) {
			return false;
		}

		$categories = $this->categoryFactory->create()->getCollection()
			->addFieldToFilter('entity_id', array('in' => $cats));

		return $categories;
	}

    /**
     * Retrieve event by product
     * @param  \Magento\Catalog\Model\Product $product
     * @return \Plumrocket\PrivateSale\Model\Indexer
     */
    public function getEventByProduct($product)
    {
    	if (!isset($this->_events[$product->getId()])) {
    		$events = $this->getEventsByProduct($product);
    	}

    	if ($this->_events[$product->getId()] && count($this->_events[$product->getId()])) {
    		return $this->_events[$product->getId()]->getFirstItem();
    	}
    	return null;
    }

    /**
     * Retrieve store id
     * @return int
     */
    public function getStoreId()
    {
    	return $this->storeManager->getStore()->getId();
    }

    /**
     * Rerieve event action
     * @param  Plumrocket_Pricatesale_Model_Indexer $event
     * @return int
     */
    protected function _getEventAction($event)
    {
    	$action = false;
    	$currentTime = $this->getCurrentDate();
		$start = $this->toTime($event->getStartDate());
		$end = $this->toTime($event->getEndDate());

		if ($start > $currentTime
			&& $event->getEventStartAction() == \Plumrocket\PrivateSale\Model\Config\Source\Beforeeventstart::DISPLAY_NO
		) {
			$action = \Plumrocket\PrivateSale\Model\Config\Source\Eventend::NOT_FOUND;
		} elseif ($end < $currentTime) {

			if ($event->getEventEndAction() != \Plumrocket\PrivateSale\Model\Config\Source\Eventend::DO_NOTHING) {
				$action = $event->getEventEndAction();
			}
		}

		return $action;
    }

    /**
     * Convert date to timestamp
     * @param  stirng $date
     * @return int
     */
    public function toTime($date)
    {
    	return strtotime($date);
    }

    /**
     * Retrieve data from config
     * This method call default Mage::getStoreConfig()
     * @param  string $type
     * @return int
     */
    protected function _getConfig($type)
    {
    	switch ($type) {
    		case self::CONFIG_END_ACTION:
    			$path = \Plumrocket\PrivateSale\Model\Attribute\Source\Product\Eventend::CONFIG_PATH;
    			break;
    		case self::CONFIG_START_ACTION:
    			$path = \Plumrocket\PrivateSale\Model\Config\Source\Beforeeventstart::CONFIG_PATH;
    			break;
    		default:
    			$path = \Plumrocket\PrivateSale\Model\Attribute\Source\Product\Eventend::CONFIG_PATH;
    			break;
    	}

    	/**
    	 * See description to method getUseConfig
    	 */
    	$this->setUseConfig(true);
    	return $this->helper->getConfig($path);
    }

    /**
     * Why this method is neaded? In database we write end result of event start or event end
     * It means, that if for category setted "Use config" for product in indexer table will be writed data from config
     * When category or product save it generated and rewrited, but when saved config data not changed
     * In this case when we save config, we must change value of action if setted "use_config"
     * @return boolan
     */
    public function getUseConfig()
    {
    	$useConfig = $this->getData('use_config');
    	$this->setUseConfig(false);
    	return $useConfig;
    }
}
