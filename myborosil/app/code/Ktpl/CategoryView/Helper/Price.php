<?php

namespace Ktpl\CategoryView\Helper;

class Price extends \Magento\Framework\App\Helper\AbstractHelper 
{
    /**
     * @var Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var Magento\Framework\Pricing\Helper\Data
     */
    protected $_priceHelper;
    
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Pricing\Helper\Data $priceHelper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper
    ) {
        parent::__construct($context);
        $this->_productFactory = $productFactory;
        $this->_priceHelper = $priceHelper;
    }
 
    /**
     * Retrieve Product
     * @param \Magento\Catalog\Model\Product $_product
     * @return string
     */
    public function getConfigurableProductPriceRange($_product)
    {
    	$_minPrice = 0; $_maxPrice = 0;

        $typeInstance = $_product->getTypeInstance()
        	->setStoreFilter($_product->getStoreId(), $_product);

        $optionAttribute = $_product->getTypeInstance()->getConfigurableAttributes($_product);

        $optionData = $typeInstance->getConfigurableOptions($_product);
        
        foreach($optionAttribute as $attribute)
        {
            $attributeId = $attribute->getAttributeId();
            if(isset($optionData[$attributeId]))
            {
                foreach($optionData[$attributeId] as $option)
                {
                    $_productOption = $this->_productFactory->create();
                    $_productId = $_productOption->getIdBySku($option['sku']);

                    if(isset($_productId))
                    {
                        $_productOption->load($_productId);

                        if($_productOption->getId())
                        {
                            $_finalPrice = $_productOption->getFinalPrice();

                            if($_finalPrice < $_minPrice || $_minPrice == 0)
                                $_minPrice = $_finalPrice;

                            if($_finalPrice > $_maxPrice)
                                $_maxPrice = $_finalPrice;
                        }
                    }
                }
            }
        }

        return $this->_priceHelper->currency($_minPrice, true, false) . " - " . $this->_priceHelper->currency($_maxPrice, true, false);
    }
}