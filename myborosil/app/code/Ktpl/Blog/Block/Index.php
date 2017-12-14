<?php

namespace Ktpl\Blog\Block;

use Magento\Store\Model\ScopeInterface;

/**
 * Blog index block
 */
class Index extends \Ktpl\Blog\Block\Post\PostList
{
    /**
     * Preparing global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->_addBreadcrumbs();
        $this->pageConfig->getTitle()->set($this->_getConfigValue('title'));
        $this->pageConfig->setKeywords($this->_getConfigValue('meta_keywords'));
        $this->pageConfig->setDescription($this->_getConfigValue('meta_description'));
        $this->pageConfig->addRemotePageAsset(
            $this->_url->getBaseUrl(),
            'canonical',
            ['attributes' => ['rel' => 'canonical']]
        );

        return parent::_prepareLayout();
    }

    /**
     * Retrieve blog title
     * @return string
     */
    protected function _getConfigValue($param)
    {
        return $this->_scopeConfig->getValue(
            'mfblog/index_page/'.$param,
            ScopeInterface::SCOPE_STORE
        );
    }

}
