<?php

namespace Ktpl\Blog\Helper;

use Magento\Framework\App\Action\Action;

/**
 * Ktpl Blog Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Ktpl\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    protected $_postCollectionFactory;

    /**
     * @var Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Cms\Model\Page $post
     * @param \Magento\Framework\Registry $coreRegistry,
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Cms\Model\PageFactory $postFactory
     * @param \Ktpl\Blog\Model\Url $url
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Ktpl\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        \Magento\Framework\Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_postCollectionFactory = $postCollectionFactory;

    }

    /**
     * Retrieve translated & formated date
     * @param  string $format
     * @param  string $dateOrTime
     * @return string
     */
    public static function getTranslatedDate($format, $dateOrTime)
    {
    	$time = is_numeric($dateOrTime) ? $dateOrTime : strtotime($dateOrTime);
        $month = ['F' => '%1', 'M' => '%2'];

        foreach ($month as $from => $to) {
            $format = str_replace($from, $to, $format);
        }

        $date = date($format, $time);

        foreach ($month as $to => $from) {
            $date = str_replace($from, __(date($to, $time)), $date);
        }

        return $date;
    }

    public function isWishlisted($postId)
    {
        // called prepare sortable parameters
        $collection = $this->_postCollectionFactory->create();

        $collection->getSelect()->join(
            ['wishlist' => 'ktpl_blog_post_wishlist'],
            'main_table.post_id = wishlist.post_id'
        );

        $collection->getSelect()->join(
            ['customer' => 'customer_entity'],
            'wishlist.customer_id = customer.entity_id'
        );

        $collection->addFieldToFilter('post_id', array('eq' => $postId));

        return $postCount = ($collection->count() >= 1) ? false : true ;
    }
}
