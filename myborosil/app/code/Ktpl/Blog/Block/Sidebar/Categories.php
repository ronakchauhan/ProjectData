<?php

namespace Ktpl\Blog\Block\Sidebar;

use Magento\Store\Model\ScopeInterface;

/**
 * Blog sidebar categories block
 */
class Categories extends \Magento\Framework\View\Element\Template
{
    use Widget;

    /**
     * @var string
     */
    protected $_widgetKey = 'categories';

    /**
     * @var \Ktpl\Blog\Model\ResourceModel\Category\Collection
     */
    protected $_categoryCollection;

    protected $_request;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Ktpl\Blog\Model\ResourceModel\Category\Collection $categoryCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ktpl\Blog\Model\ResourceModel\Category\Collection $categoryCollection,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_request = $request;
        $this->_categoryCollection = $categoryCollection;
    }

    /**
     * Get grouped categories
     * @return \Ktpl\Blog\Model\ResourceModel\Category\Collection
     */
    public function getGroupedChilds()
    {
        $k = 'grouped_childs';
        if (!$this->hasData($k)) {
            $array = $this->_categoryCollection
                ->addActiveFilter()
                ->addStoreFilter($this->_storeManager->getStore()->getId())
                ->setOrder('position')
                ->getTreeOrderedArray();

            $this->setData($k, $array);
        }

        return $this->getData($k);
    }

    /**
     * Retrieve true if need to show posts count
     * @return int
     */
    public function showPostsCount()
    {
        $key = 'show_posts_count';
        if (!$this->hasData($key)) {
            $this->setData($key, (bool)$this->_scopeConfig->getValue(
                'mfblog/sidebar/'.$this->_widgetKey.'/show_posts_count', ScopeInterface::SCOPE_STORE
            ));
        }
        return $this->getData($key);
    }


    /**
     * Retrieve block identities
     * @return array
     */
    public function getIdentities()
    {
        return [\Magento\Cms\Model\Block::CACHE_TAG . '_blog_categories_widget'  ];
    }
}
