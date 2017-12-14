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
 * @package     Plumrocket Private Sales
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\PrivateSale\Model;

use Plumrocket\PrivateSale\Model\Attribute\Source\PrivateEvent as PrivateEventConfig;
use Plumrocket\PrivateSale\Model\Attribute\Source\EventLanding;

class PrivateEvent extends \Magento\Framework\Model\AbstractModel
{

	/**
	 * Customer Url
	 * @var Magento\Customer\Model\Url
	 */
	protected $customerUrl;

	/**
	 * Cms page helper
	 * @var Magento\Cms\Helper\Page
	 */
	protected $pageHelper;

	/**
	 * Data helper
	 * @var Plumrocket\PrivateSale\Helper\Data
	 */
	protected $dataHelper;

	/**
	 * Splash page
	 * @var \Plumrocket\PrivateSale\Model\Splashpage
	 */
	protected $splashpage;

	/**
	 * Customer Session
	 * @var \Magento\Customer\Model\Session
	 */
	protected $customerSession;

	/**
	 * Store Manager
	 * @var Magento\Store\Model\StoreManagerInterface
	 */
	protected $storeManager;

	/**
	 * Category Factory
	 * @var \Magento\Catalog\Model\CategoryFactory
	 */
	protected $categoryFactory;

	/**
	 * Constructor
	 * @param \Magento\Customer\Model\Session            $customerSession
	 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
	 * @param \Plumrocket\PrivateSale\Model\Splashpage   $splashpage
	 * @param \Magento\Customer\Model\Url                $customerUrl
     * @param \Magento\Catalog\Model\CategoryFactory 	 $categoryFactory,
	 * @param \Magento\Cms\Helper\Page                   $pageHelper
	 * @param \Plumrocket\PrivateSale\Helper\Data        $dataHelper
	 * @param \Magento\Framework\Model\Context           $context
	 * @param \Magento\Framework\Registry                $registry
	 * @param array                                      $data
	 */
    public function __construct(
    	\Magento\Customer\Model\Session $customerSession,
    	\Magento\Store\Model\StoreManagerInterface $storeManager,
    	\Plumrocket\PrivateSale\Model\Splashpage $splashpage,
    	\Magento\Customer\Model\Url $customerUrl,
    	\Magento\Catalog\Model\CategoryFactory $categoryFactory,
    	\Magento\Cms\Helper\Page $pageHelper,
    	\Plumrocket\PrivateSale\Helper\Data $dataHelper,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
    	$this->customerUrl = $customerUrl;
    	$this->dataHelper = $dataHelper;
    	$this->categoryFactory = $categoryFactory;
    	$this->splashpage = $splashpage;
    	$this->storeManager = $storeManager;
    	$this->customerSession = $customerSession;
    	$this->pageHelper = $pageHelper;
    	parent::__construct(
    		$context,
    		$registry,
    		null,
    		null,
    		$data
    	);
    }

	/**
	 * Check if preset category is private event
	 * @param  Magento\Catalog\Model\Category  $category
	 * @return boolean
	 */
	public function isCategoryPrivateEvent($category)
	{
		if (!$this->_allow()) {
			return false;
		}

		if ($category->getPrivatesalePrivateEvent() == PrivateEventConfig::PRIVATE_YES) {
			if (!$this->customerBelongsToGroup($category)) {
				return true;
			}
		} elseif ($category->getPrivatesalePrivateEvent() == PrivateEventConfig::PRIVATE_NO) {
			return false;
		} else { //Use parent

			$parentCategories = $this->categoryFactory->create()->getCollection()
                ->addFieldToFilter('entity_id', ['in' => $category->getParentIds()])
                ->addAttributeToSelect('privatesale_event_landing')
                ->addAttributeToSelect('privatesale_private_event')
                ->addAttributeToSelect('privatesale_restrict_cgroup')
                ->setOrder('level', 'DESC');


	        foreach ($parentCategories as $pCat) {
	        	if ($pCat->getPrivatesalePrivateEvent() == PrivateEventConfig::PRIVATE_YES) {
	        		if (!$this->customerBelongsToGroup($pCat)) { 
	        			$category->setPrivateEventParentCategory($pCat);
	        			return true;
	        		}
	        	} elseif ($pCat->getPrivatesalePrivateEvent() == PrivateEventConfig::PRIVATE_NO) {
	        		return false;
	        	}
	        }

		}

		return false;
	}

	/**
	 * Retrieve tru if category is private event and customer is not logged in
	 * @param  Magento\Catalog\Model\Category $category
	 * @return boolean
	 */
	public function isCategoryLocked($category)
	{
		return !$this->customerSession->isLoggedIn()
			&& $this->isCategoryPrivateEvent($category);
	}

	/**
	 * Retrieve redirect url
	 * @param  Magento\Catalog\Model\Category $category
	 * @return string
	 */
	public function getRedirectUrl($category)
	{
		if ($category->getPrivatesalePrivateEvent() == PrivateEventConfig::PRIVATE_YES) {
			$landing = $category->getPrivatesaleEventLanding();
			return $this->_getRedirectUrlByLanding($landing);
		} {
			$category = $category->getPrivateEventParentCategory();
			if ($category) {
				$landing = $category->getPrivatesaleEventLanding();
				return $this->_getRedirectUrlByLanding($landing);
			}
		}
	}

	/**
	 * Retrieve redirect url by landing
	 * @param  sttring $landing
	 * @return string
	 */
	protected function _getRedirectUrlByLanding($landing)
	{
		if ($landing === null) {
			return $this->storeManager->getStore()->getBaseUrl();
		}

		if ($landing == Eventlanding::DISPLAY_LOGIN_PAGE) {
			return $this->customerUrl->getLoginUrl();
		} elseif ($landing == Eventlanding::DISPLAY_REGISTER_PAGE) {
			return $this->customerUrl->getRegisterUrl();
		} else {
			return $this->pageHelper->getPageUrl($landing);
		}
	}

	/**
	 * Check if preset product is private event
	 * @param  Magento\Catalog\Model\Product  $product
	 * @return boolean
	 */
	public function isProductPrivateEvent($product)
	{
		if (!$this->_allow()) {
			return false;
		}

		if ($product->getPrivatesalePrivateEvent() == PrivateEventConfig::PRIVATE_YES) {
			if (!$this->customerBelongsToGroup($product)) {
				return true;
			}
		} elseif ($product->getPrivatesalePrivateEvent() == PrivateEventConfig::PRIVATE_NO) {
			return false;
		} else { //Use parent
			$parentCategories = $this->categoryFactory->create()->getCollection()
                ->addFieldToFilter('entity_id', ['in' => $product->getCategoryIds()])
                ->addAttributeToSelect(['privatesale_event_landing','privatesale_private_event', 'privatesale_restrict_cgroup']);

            $allCatIds = array();
            $privatYes = false;
            $privatNo = false;
            foreach ($parentCategories as $pCat) {
            	if ($pCat->getPrivatesalePrivateEvent() == PrivateEventConfig::PRIVATE_YES) {
            		if (!$this->customerBelongsToGroup($pCat)) {
	        			$product->setPrivateEventParentCategory($pCat);
	        			$privatYes = true;
	        		}
	        	}
	        	if ($pCat->getPrivatesalePrivateEvent() == PrivateEventConfig::PRIVATE_NO) {
	        		$privatNo = true;
	        	}
	        	$allCatIds = array_merge($allCatIds, $pCat->getParentIds());
            }

            if ($privatYes) {
            	return true;
            }

            if ($privatNo) {
            	return false;
            }

            $_cats = $this->categoryFactory->create()->getCollection()
            	->addFieldToFilter('entity_id', ['in' => $allCatIds])
            	->addAttributeToSelect('privatesale_event_landing')
                ->addAttributeToSelect('privatesale_private_event')
                ->addAttributeToSelect('privatesale_restrict_cgroup')
                ->setOrder('level', 'DESC');

            foreach ($_cats as $_cat) {
            	if ($_cat->getPrivatesalePrivateEvent() == PrivateEventConfig::PRIVATE_YES) {
            		if (!$this->customerBelongsToGroup($_cat)) {
	        			$product->setPrivateEventParentCategory($_cat);
	        			return true;
	        		}
	        	} elseif ($_cat->getPrivatesalePrivateEvent() == PrivateEventConfig::PRIVATE_NO) {
	        		return false;
	        	}
            }
		}

		return false;
	}

	/**
	 * Retrieve product redirect url
	 * @param  Magento\Catalog\Model\Product $product
	 * @return string
	 */
	public function getProductRedirectUrl($product)
	{
		if ($product->getPrivatesalePrivateEvent() == PrivateEventConfig::PRIVATE_YES) {
			$landing = $product->getPrivatesaleEventLanding();
			return $this->_getRedirectUrlByLanding($landing);
		} {
			$category = $product->getPrivateEventParentCategory();
			if ($category) {
				$landing = $category->getPrivatesaleEventLanding();
				return $this->_getRedirectUrlByLanding($landing);
			}
		}

		return '';
	}

    /**
     * Allow
     * @return bollean
     */
    protected function _allow()
    {
    	$isEnabledSplash = $this->splashpage->getEnabledPage();

    	if (!$this->dataHelper->moduleEnabled() || $isEnabledSplash) {
    		return false;
    	}
    	return true;
    }

    /**
     * The customer belongs to the group
     * @return bollean
     */
    protected function customerBelongsToGroup($object) 
    {
    	if ($customerGroupId = $this->customerSession->getCustomer()->getGroupId()) {
    		if(in_array($customerGroupId, explode(',', $object->getPrivatesaleRestrictCgroup()))) {
    			return true;
    		}
    	}

    	return false;
    }
}
