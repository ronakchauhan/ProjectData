<?php

namespace Ktpl\Additional\Block\Product;

use \Magento\Store\Model\ScopeInterface;

class View extends \Magento\Catalog\Block\Product\View
{
        public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Block\Product\Context $productContext,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        array $data = []
    ) {

        $this->_storeManager = $context->getStoreManager();
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_blockFactory = $blockFactory;

        parent::__construct($productContext, $urlEncoder, $jsonEncoder, $string, $productHelper, $productTypeConfig, $localeFormat, $customerSession, $productRepository, $priceCurrency, $data);
    }

    public function getProductData()
    {
        $product = $this->getProduct();

        return $product;    
    }
}