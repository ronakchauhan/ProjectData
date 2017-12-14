<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ktpl\ProductView\Helper;

use Magento\Catalog\Model\Product;

/**
 * Class Data
 * Helper class for getting options
 *
 */
class ConfigureData extends \Magento\ConfigurableProduct\Helper\Data
{
    /**
     * Catalog Image Helper
     *
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    protected $_productloader;

    /**
     * Deprecated property. Do not use it.
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @param \Magento\Catalog\Helper\Image $imageHelper
     */
    public function __construct(
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\CatalogInventory\Api\StockStateInterface $stockState,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider
    )
    {        
        $this->stockState = $stockState;
        $this->_productloader = $_productloader;
        $this->_filterProvider = $filterProvider;
        parent::__construct($imageHelper);
    }

    public function getOptionsDescription($currentProduct, $allowedProducts)
    {
        $options = [];
        foreach ($allowedProducts as $product) {
            $productId = $product->getId();
            $productDetail = $this->_productloader->create()->load($productId);
            $description = $productDetail->getDescription();

            $options['description'][$productId][] = $description;
        }
        return $options;
    }

    public function getProductDescription($_product)
    {
        $desc = $_product->getDescription();
        $key = 'product_desc_filtered_content';
        if (!$this->hasData($key)) {
            $content = $this->_filterProvider->getPageFilter()->filter(
                $desc
            );
            $this->setData($key, $content);
        }
        return $this->getData($key);
    }
}
