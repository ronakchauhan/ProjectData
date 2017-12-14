<?php

namespace Ktpl\ProductView\Observer\Catalog\Product;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Ktpl\ProductView\Model\Catalog\Product\Attribute\Source\View\Layout;

/**
 * Customer log observer.
 */
class AddCustomViewHandle implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\Registry $registry
    )
    {
        $this->_registry = $registry;
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
        if ($action != 'catalog_product_view' && $action != 'quickview_catalog_product_view')
            return $this;

        $product = $this->_registry->registry('product');
        if (!$product)
            return $this;

        $listingLayout = $product->getViewLayout();
        if(is_null($listingLayout) || $listingLayout == Layout::VALUE_DEFAULT_LAYOUT)
            return $this;

        $layout = $observer->getData('layout');
        $layout->getUpdate()->addHandle('catalog_product_view_' . $listingLayout);

        return $this;
    }
}
