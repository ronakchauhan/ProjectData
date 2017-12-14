<?php

namespace Ktpl\CategoryView\Observer\Catalog\Category;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Ktpl\CategoryView\Model\Catalog\Category\Attribute\Source\Listing\Layout;

class AddCustomListingHandle implements ObserverInterface
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
        if ($action != 'catalog_category_view')
            return $this;

        $category = $this->_registry->registry('current_category');
        if (!$category)
            return $this;

        $listingLayout = $category->getListingLayout();
        if(is_null($listingLayout))
            return $this;

        $layout = $observer->getData('layout');
        if($listingLayout == Layout::VALUE_DEFAULT_LAYOUT){
            $layout->getUpdate()->addHandle('catalog_category_view_default');   
        }
        else{
            $layout->getUpdate()->addHandle('catalog_category_view_' . $listingLayout);
        }

        return $this;
    }
}
