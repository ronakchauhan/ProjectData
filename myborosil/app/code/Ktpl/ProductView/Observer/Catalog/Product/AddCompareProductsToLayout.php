<?php

namespace Ktpl\ProductView\Observer\Catalog\Product;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Ktpl\ProductView\Model\Catalog\Product\Attribute\Source\View\Layout;

/**
 * Customer log observer.
 */
class AddCompareProductsToLayout implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * Item collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory
     */
    protected $_itemCollectionFactory;

    /**
     * Customer session
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * Customer visitor
     *
     * @var \Magento\Customer\Model\Visitor
     */
    protected $_customerVisitor;

    /**
     * Catalog product compare list
     *
     * @var \Magento\Catalog\Model\Product\Compare\ListCompare
     */
    protected $_catalogProductCompareList;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\Visitor $customerVisitor
     * @param \Magento\Catalog\Model\Product\Compare\ListCompare $catalogProductCompareList
     * @param ProductRepositoryInterface $productRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Visitor $customerVisitor,
        \Magento\Catalog\Model\Product\Compare\ListCompare $catalogProductCompareList,
        ProductRepositoryInterface $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_registry = $registry;
        $this->_itemCollectionFactory = $itemCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_customerVisitor = $customerVisitor;
        $this->_catalogProductCompareList = $catalogProductCompareList;
        $this->productRepository = $productRepository;
        $this->_storeManager = $storeManager;
    }

    /**
     * Handler for 'customer_logout' event.
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {        
        $action = $observer->getData('full_action_name');
        if ($action != 'catalog_product_view')
            return $this;

        $product = $this->_registry->registry('product');
        if (!$product)
            return $this;

        $listingLayout = $product->getViewLayout();
        if(is_null($listingLayout) || $listingLayout != Layout::VALUE_LAYOUT_4)
            return $this;

        /** @var \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\Collection $items */
        $items = $this->_itemCollectionFactory->create();

        if ($this->_customerSession->isLoggedIn()) {
            $items->setCustomerId($this->_customerSession->getCustomerId());
        } else {
            $items->setVisitorId($this->_customerVisitor->getId());
        }

        try {
            $items->clear();
        }
        catch (\Exception $e) { }

        $compareProductList = $product->getCompareProductList();
        
        if(isset($compareProductList))
        {
            $this->_catalogProductCompareList->addProduct($product);

            $this->_catalogProductCompareList->addProducts($compareProductList);
        }

        return $this;
    }
}
