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

namespace Plumrocket\PrivateSale\Controller\Adminhtml;

class Preview extends \Magento\Framework\App\Action\Action
{
    /**
     * Date
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * Preview helper
     * @var  \Plumrocket\PrivateSale\Helper\Preview
     */
    protected $previewHelper;

    /**
     * Store manager
     * @var \Magento\Store\Model\StoreManager
     */
    protected $storeManager;

    /**
     * Resource
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * Contructor
     * @param \Magento\Framework\App\Action\Context       $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\App\ResourceConnection   $resource
     * @param \Magento\Store\Model\StoreManager           $storeManager
     * @param \Plumrocket\PrivateSale\Helper\Preview      $previewHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Store\Model\StoreManager $storeManager,
        \Plumrocket\PrivateSale\Helper\Preview $previewHelper
    ) {
        $this->resource = $resource;
        $this->storeManager = $storeManager;
        $this->date = $date;
        $this->previewHelper = $previewHelper;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {

    }

    /**
     * Retrieve store id
     * @return int
     */
    protected function _getStoreId()
    {
        if ($this->getRequest()->getParam('store')) {
            return $this->getRequest()->getParam('store');
        } else {
            $store = $this->storeManager->getDefaultStoreView();
            // Gets the current store's id
            return $store->getStoreId();
        }
    }

    /**
     * Added parameter to url
     * @param string $url
     */
    protected function _addParamToUrl($url)
    {
        $sep = '?';
        if (strpos($url, '?') !== false ) {
            $sep = '&';
        }

        $value = $this->_getValue();
        $url .= $sep . $this->previewHelper->getPreviewKey() . '=' . $value;
        return $url;
    }

    /**
     * Redirect to url
     * @param  string $url
     * @return void
     */
    protected function _redirectUrl($url)
    {
        $url = $this->_addParamToUrl($url);

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($url);
        return $resultRedirect;
    }

    /**
     * Retrieve and generate code
     * @return string
     */
    protected function _getValue()
    {
        $id = $this->getRequest()->getParam('id');
        // code
        $code = md5(time() . time() . 'popy-pop');

        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        // clear
        $connection->query(sprintf("DELETE FROM `%s` WHERE active_to < '%s';",
            $this->resource->getTableName('plumrocket_privatesale_preview_access'),
            strftime('%F %T', $this->date->timestamp(time()))
        ));

        // insert new
        $connection->query(sprintf("INSERT INTO %s (`code`, `active_to`) VALUES ('%s', '%s');",
            $this->resource->getTableName('plumrocket_privatesale_preview_access'),
            $code,
            strftime('%F %T', $this->date->timestamp(time() + 600))
        ));

        return $code;
    }
}
