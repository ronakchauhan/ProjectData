<?php

namespace Ktpl\Blog\Block\Widget;

/**
 * Blog recent posts widget
 */
class Recent extends \Ktpl\Blog\Block\Post\PostList\AbstractList implements \Magento\Widget\Block\BlockInterface
{

    /**
     * @var \Ktpl\Blog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Ktpl\Blog\Model\Category
     */
    protected $_category;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Ktpl\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory
     * @param \Ktpl\Blog\Model\Url $url
     * @param \Ktpl\Blog\Model\CategoryFactory $categoryFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Ktpl\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        \Ktpl\Blog\Model\Url $url,
        \Ktpl\Blog\Model\CategoryFactory $categoryFactory,
        array $data = []
    ) {
        parent::__construct($context, $coreRegistry, $filterProvider, $postCollectionFactory, $url, $data);
        $this->_categoryFactory = $categoryFactory;
    }

    /**
     * Set blog template
     *
     * @return this
     */
    public function _toHtml()
    {
        $this->setTemplate(
            $this->getData('custom_template') ?: 'widget/recent.phtml'
        );

        return parent::_toHtml();
    }

    /**
     * Retrieve block title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData('title') ?: __('Recent Blog Posts');
    }

    /**
     * Prepare posts collection
     *
     * @return void
     */
    protected function _preparePostCollection()
    {
        $size = $this->getData('number_of_posts');
        if (!$size) {
            $size = (int) $this->_scopeConfig->getValue(
                'mfblog/sidebar/recent_posts/posts_per_page',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        }

        $this->setPageSize($size);

        parent::_preparePostCollection();

        if ($category = $this->getCategory()) {
            $categories = $category->getChildrenIds();
            $categories[] = $category->getId();
            $this->_postCollection->addCategoryFilter($categories);
        }
    }

    /**
     * Retrieve category instance
     *
     * @return \Ktpl\Blog\Model\Category
     */
    public function getCategory()
    {
        if ($this->_category === null) {
            if ($categoryId = $this->getData('category_id')) {
                $category = $this->_categoryFactory->create();
                $category->load($categoryId);

                $storeId = $this->_storeManager->getStore()->getId();
                if ($category->isVisibleOnStore($storeId)) {
                    $category->setStoreId($storeId);
                    return $this->_category = $category;
                }
            }

            $this->_category = false;
        }

        return $this->_category;
    }

    /**
     * Retrieve post short content
     * @param  \Ktpl\Blog\Model\Post $post
     *
     * @return string
     */
    public function getShorContent($post)
    {
        return $post->getShortFilteredContent();
    }
}
