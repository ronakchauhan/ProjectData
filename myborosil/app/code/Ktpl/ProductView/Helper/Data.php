<?php

namespace Ktpl\ProductView\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper 
{
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;
    
    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    protected $imageBuilder; 

    protected $_registry;

    protected $_request;
    
    /**
     * @param \Magento\Framework\App\Helper\Context $context,
     * @param \Magento\Catalog\Model\ProductFactory $productFactory,
     * @param \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request
    ) {
        parent::__construct($context);
        $this->_productFactory = $productFactory;
        $this->imageBuilder = $imageBuilder;
        $this->_registry = $registry;
        $this->_request = $request;
    }
 
    /**
     * Retrieve product image
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }

    /**
     * Retrieve product image
     *
     * @param $options
     * @return int[]
     */
    public function getBundleProducts($options)
    {
        $optionValues = ['options' => [], 'product_ids' => []];

        foreach ($options as $_option)
        {
            if (!$_option->getSelections()) continue;
            if($_option->getType() != "multi") continue;

            $optionArray = [ 'id' => $_option->getId(), 'title' => $_option->getTitle(), 'option_label' => $_option->getOptionLabel(),'product_ids' => [] ];
            $_selections = $_option->getSelections();
            foreach ($_selections as $_selection)
            {
                $optionValues['product_ids'][] = $_selection->getProductId();
                $optionArray['product_ids'][] = $_selection->getProductId();
            }
            $optionValues['options'][] = $optionArray;
        }

        $optionValues['product_ids'] = array_unique($optionValues['product_ids']);
        return $optionValues;
    }

    /**
     * Retrieve product
     *
     * @param int $productId
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct($productId)
    {
        $_product = $this->_productFactory->create();
        $_product->load($productId);
        return $_product;
    }

    /**
     * Retrieve product
     *
     * @param int $productId
     * @return \Magento\Catalog\Model\Product
     */
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getCurrentProductPage()
    {
        return $this->_request->getFullActionName();
    }
}   