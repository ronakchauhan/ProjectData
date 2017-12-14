<?php

namespace Ktpl\ImprovedLayeredNavigation\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper 
{

    protected $registry;
 
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->registry = $registry;
    }
    
    /**
     * Get layer object
     *
     * @return \Magento\Catalog\Model\Layer
     */
    public function getCurrentCategoryName()
    {
        $category = $this->registry->registry('current_category');
        return $category->getName();
    }
}