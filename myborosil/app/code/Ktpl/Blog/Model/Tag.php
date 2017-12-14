<?php

namespace Ktpl\Blog\Model;

use Ktpl\Blog\Model\Url;

/**
 * Tag model
 *
 * @method \Ktpl\Blog\Model\ResourceModel\Tag _getResource()
 * @method \Ktpl\Blog\Model\ResourceModel\Tag getResource()
 * @method string getTitle()
 * @method $this setTitle(string $value)
 * @method string getIdentifier()
 * @method $this setIdentifier(string $value)
 */
class Tag extends \Magento\Framework\Model\AbstractModel
{

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'ktpl_blog_tag';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'blog_tag';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Ktpl\Blog\Model\Url $url
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        Url $url,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_url = $url;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ktpl\Blog\Model\ResourceModel\Tag');
    }

    /**
     * Check if category identifier exist for specific store
     * return category id if category exists
     *
     * @param string $identifier
     * @return int
     */
    public function checkIdentifier($identifier)
    {
        return $this->load($identifier)->getId();
    }

    /**
     * Retrieve catgegory url route path
     * @return string
     */
    public function getUrl()
    {
        return $this->_url->getUrlPath($this, URL::CONTROLLER_TAG);
    }

    /**
     * Retrieve tag url
     * @return string
     */
    public function getTagUrl()
    {
        return $this->_url->getUrl($this, URL::CONTROLLER_TAG);
    }

}
