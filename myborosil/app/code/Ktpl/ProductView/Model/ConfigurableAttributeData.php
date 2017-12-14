<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ktpl\ProductView\Model;

use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class ConfigurableAttributeData
 */
class ConfigurableAttributeData extends \Magento\ConfigurableProduct\Model\ConfigurableAttributeData
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    protected $_helper;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\Helper\Data $helper
    ) {
        $this->productRepository = $productRepository;
        $this->_helper           = $helper;
    }

    /**
     * @param Attribute $attribute
     * @param array $config
     * @return array
     */
    protected function getAttributeOptionsData($attribute, $config)
    {
        $attributeOptionsData = [];
        foreach ($attribute->getOptions() as $attributeOption) {
            $optionId = $attributeOption['value_index'];
            $productId = isset($config[$attribute->getAttributeId()][$optionId])? $config[$attribute->getAttributeId()][$optionId] : [];
            $serves = $this->getProductServesData($productId);

            $attributeOptionsData[] = [
                'id' => $optionId,
                'label' => $attributeOption['label'],
                'products' => isset($config[$attribute->getAttributeId()][$optionId])
                    ? $config[$attribute->getAttributeId()][$optionId]
                    : [],
                'serves' => $serves,
                'finalPrice' => $this->getProductPrice($productId),
            ];
        }
        return $attributeOptionsData;
    }

    public function getProductServesData($productid)
    {
        $product = $this->productRepository->getById($productid[0]);
        return $product->getServer();
    }

    public function getProductPrice($productid)
    {
        $product = $this->productRepository->getById($productid[0]);
        return $this->_helper->currency($product->getFinalPrice(), true, false);
    }
}
