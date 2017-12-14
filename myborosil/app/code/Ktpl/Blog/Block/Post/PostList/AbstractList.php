<?php

namespace Ktpl\Blog\Block\Post\PostList;

use Magento\Store\Model\ScopeInterface;

/**
 * Abstract blog post list block
 */
abstract class AbstractList extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $_post;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Ktpl\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    protected $_postCollectionFactory;

    /**
     * @var \Ktpl\Blog\Model\ResourceModel\Post\Collection
     */
    protected $_postCollection;

    /**
     * @var \Ktpl\Blog\Model\Url
     */
    protected $_url;

    /**
     * @var \Ktpl\Blog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Ktpl\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory
     * @param \Ktpl\Blog\Model\Url $url
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Ktpl\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        \Ktpl\Blog\Model\Url $url,
        \Ktpl\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_coreRegistry = $coreRegistry;
        $this->_filterProvider = $filterProvider;
        $this->_postCollectionFactory = $postCollectionFactory;
        $this->_url = $url;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Prepare posts collection
     *
     * @return void
     */
    protected function _preparePostCollection()
    {
        $this->_postCollection = $this->_postCollectionFactory->create()
            ->addActiveFilter()
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('main_table.publish_time', 'DESC');

        if ($this->getPageSize()) {
            $this->_postCollection->setPageSize($this->getPageSize());
        }
    }

    /**
     * Prepare posts collection
     *
     * @return \Ktpl\Blog\Model\ResourceModel\Post\Collection
     */
    public function getPostCollection()
    {
        if (is_null($this->_postCollection)) {
            $this->_preparePostCollection();
        }

        return $this->_postCollection;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->_scopeConfig->getValue(
            \Ktpl\Blog\Helper\Config::XML_PATH_EXTENSION_ENABLED,
            ScopeInterface::SCOPE_STORE
        )) {
            return '';
        }

        return parent::_toHtml();
    }
}
