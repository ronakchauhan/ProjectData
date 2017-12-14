<?php

namespace Ktpl\ProductView\Block\Product\View;

use \Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Attributes extends \Magento\Catalog\Block\Product\View\Attributes
{
        /**
     * @var Product
     */
    protected $_product = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * Deprecated property. Do not use it.
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        array $data = []
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->_coreRegistry = $registry;
        $this->_filterProvider = $filterProvider;
        parent::__construct($context, $registry, $priceCurrency, $data);
    }

    /**
     * Retrieve filtered content
     *
     * @return string
     */
    public function getProductCare()
    {   
        $care = $this->getProduct()->getCare();
        $key = 'care_filtered_content';
        if (!$this->hasData($key)) {
            $content = $this->_filterProvider->getPageFilter()->filter(
                $care
            );
            $this->setData($key, $content);
        }
        return $this->getData($key);
    }

    /**
     * Retrieve filtered content
     *
     * @return string
     */
    public function getPromotionalBanners()
    {   
        $promotionalBanner = $this->getProduct()->getPromotionalBanner();
        $key = 'promotional_banner_filtered_content';
        if (!$this->hasData($key)) {
            $content = $this->_filterProvider->getPageFilter()->filter(
                $promotionalBanner
            );
            $this->setData($key, $content);
        }
        return $this->getData($key);
    }
}