<?php

namespace Ktpl\QuickView\Plugin;

class BlockProductList
{
    const XML_PATH_QUICKVIEW_ENABLED = 'quickview/general/enabled';
    const XML_PATH_QUICKVIEW_BUTTONTEXT = 'quickview/general/button_text';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlInterface;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
        ) {
        $this->urlInterface = $urlInterface;
        $this->scopeConfig = $scopeConfig;
    }

    public function aroundGetProductDetailsHtml(
        \Magento\Catalog\Block\Product\ListProduct $subject,
        \Closure $proceed,
        \Magento\Catalog\Model\Product $product
        )
    {
        $result = $proceed($product);
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_ENABLED,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabled) {
            $buttonText = $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_BUTTONTEXT,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $productUrl = $this->urlInterface->getUrl('quickview/catalog_product/view', array('id' => $product->getId()));
            return $result . '<a class="ktpl-quickview quickview_button" data-quickview-url=' . $productUrl . ' href="javascript:void(0);"><span>' . __("$buttonText") . '</span></a>';
        }

        return $result;
    }
}
