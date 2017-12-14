<?php

namespace Ktpl\Blog\Block\Post;


class FavouritePost extends \Magento\Framework\View\Element\Template
{
    /**
     * Posts's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;


    /**
     * @var \Ktpl\Blog\Model\AuthorFactory
     */
    protected $_authorFactory;

    /**
     * @var \Ktpl\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    protected $_postCollectionFactory;

    /**
     * @var \Ktpl\Blog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * @var \Ktpl\Blog\Model\ImageFactory
     */
    protected $imageFactory;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Ktpl\Blog\Model\Url $url
     * @param \Ktpl\Blog\Model\AuthorFactory $authorFactory
     * @param \Ktpl\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ktpl\Blog\Model\ImageFactory $imageFactory,
        \Ktpl\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        \Ktpl\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->imageFactory = $imageFactory;
        $this->_postCollectionFactory = $postCollectionFactory;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
       }

    /**
     * Retrieve breadcrumbs block
     *
     * @return mixed
     */
    public function getFavouriteBlogs()
    {   
        
        $collection = $this->_postCollectionFactory->create();
        $collection->addFieldToFilter('is_favourite', array('eq' => 1));
        
        return $collection;
    }

    /**
     * Retrieve featured image url
     * @return string
     */
    public function getCategoryName($categoryId)
    {
        // echo"<pre/>"; print_r($categoryId);exit;
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addFieldToFilter('category_id', array('eq' => $categoryId[0]));

        return $collection->getFirstItem()->getData();
    }
}
