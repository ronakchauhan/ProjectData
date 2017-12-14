<?php

namespace Ktpl\Blog\Block\Sidebar;

/**
 * Blog sidebar categories block
 */
class LayeredNavigation extends \Magento\Framework\View\Element\Template
{
	use Widget;

	/**
     * @var \Ktpl\Blog\Model\ResourceModel\Tag\CollectionFactory
     */
    protected $tagCollectionFactory;

    /**
     * @var \Ktpl\Blog\Model\Url
     */
    protected $_url;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Ktpl\Blog\Model\Url $url
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ktpl\Blog\Model\ResourceModel\Tag\CollectionFactory $tagCollectionFactory,
        \Ktpl\Blog\Model\Url $url,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->tagCollectionFactory = $tagCollectionFactory;
        $this->_url = $url;
    }

    /**
     * @var string
     */
    protected $_widgetKey = 'filter';

	/**
	 * Retrieve query
	 * @return string
	 */
	public function getQuery()
	{
		return urldecode($this->getRequest()->getParam('q', ''));
	}

	/**
	 * Retrieve serch form action url
	 * @return string
	 */
	public function getFormUrl()
	{
		return $this->_url->getUrl('', \Ktpl\Blog\Model\Url::CONTROLLER_SEARCH);
	}

}
