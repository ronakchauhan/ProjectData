<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket Private Sales and Flash Sales v4.x.x
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\PrivateSale\Controller\Adminhtml\Preview;

class Category extends \Plumrocket\PrivateSale\Controller\Adminhtml\Preview
{
    /**
     * Category Factory
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;
    
    /**
     * Category constructor.
     *
     * @param \Magento\Framework\App\Action\Context       $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\App\ResourceConnection   $resource
     * @param \Magento\Store\Model\StoreManager           $storeManager
     * @param \Plumrocket\PrivateSale\Helper\Preview      $previewHelper
     * @param \Magento\Catalog\Model\CategoryFactory      $categoryFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Store\Model\StoreManager $storeManager,
        \Plumrocket\PrivateSale\Helper\Preview $previewHelper,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory
    ) {
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context, $date, $resource, $storeManager, $previewHelper);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $storeId = $this->_getStoreId();
        $category = $this->categoryFactory->create()->load($this->getRequest()->getParam('id'));

        $url = $category->setStoreId($storeId)->getUrl();
        return $this->_redirectUrl($url);
    }

}
