<?php

namespace Ktpl\QuickView\Block;

/**
 * Quickview Initialize block
 */
class Initialize extends \Magento\Framework\View\Element\Template
{
    /**
     * Returns config
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'baseUrl' => $this->getBaseUrl()
        ];
    }

    /**
     * Return base url.
     *
     * @codeCoverageIgnore
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    public function getUpdateCartUrl()
    {
        return $this->getUrl('quickview/catalog_product/updateCart');
    }
}
