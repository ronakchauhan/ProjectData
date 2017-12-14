<?php

namespace Ktpl\Blog\Block\Tag;

/**
 * Blog tags list
 */
class TagsList extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var Magento\Framework\Registry
     */
    protected $_tagCollectionFactory;

    /**
     * @var \Ktpl\Blog\Model\Url
     */
    protected $_url;

    /**
     * @var Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Framework\Registry $coreRegistry,
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Ktpl\Blog\Model\Url $url
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ktpl\Blog\Model\ResourceModel\Tag\CollectionFactory $tagCollectionFactory,
        \Ktpl\Blog\Model\ResourceModel\Tag\Type\CollectionFactory $tagTypeCollectionFactory,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Framework\Registry $coreRegistry,
        \Ktpl\Blog\Model\Url $url,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_tagCollectionFactory = $tagCollectionFactory;
        $this->tagTypeCollectionFactory = $tagTypeCollectionFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_filterProvider = $filterProvider;
        $this->_url = $url;
    }

    /**
     * Retrieve Filter Params
     * @return string
     */
    public function getFilterParams()
    {
        return $this->_request->getParam('tags');
    }

    /**
     * Retrieve tags collection
     *
     * @return \Ktpl\Blog\Model\Tags
     */
    public function getTags()
    {
        $collection = $this->_tagCollectionFactory->create();
        $collection->setOrder('tag_type_id','ASC');
        return $collection;
    }

    /**
     * Retrieve tags collection
     *
     * @return \Ktpl\Blog\Model\Tags
     */
    public function getTag($tagId)
    {
        $collection = $this->_tagCollectionFactory->create();
        $collection->addFieldToFilter('tag_id', array('eq' => $tagId));

        return $collection;
    }

    /**
     * Retrieve tags collection
     *
     * @return \Ktpl\Blog\Model\Tags
     */
    public function getTagByType($typeId)
    {   
        $collection = $this->_tagCollectionFactory->create();

        $collection->addFieldToFilter('tag_type_id', array('eq' => $typeId));

        return $collection;
    }

    /**
     * Retrieve category instance
     *
     * @return \Ktpl\Blog\Model\Category
     */
    public function getCategory()
    {
        return $this->_coreRegistry->registry('current_blog_category');
    }

    /**
     * Retrieve tag type
     *
     * @return \Ktpl\Blog\Model\Tags
     */
    public function getTagType($typeId)
    {
        
        $collection = $this->tagTypeCollectionFactory->create();
        $collection->addFieldToFilter('tag_type_id', array('eq' => $typeId));
    
        return $collection;
    }

    /**
     * Retrieve tags collection
     *
     * @return \Ktpl\Blog\Model\Tags
     */
    public function getTypes()
    {
        $collection = $this->tagTypeCollectionFactory->create();

        $collection->setOrder('tag_type_id','ASC');
    
        return $collection;
    }
}
