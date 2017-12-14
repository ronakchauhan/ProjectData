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

namespace Plumrocket\PrivateSale\Helper;

class Preview extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * Date
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * Resource
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * Constructor
     * @param \Plumrocket\PrivateSale\Helper\Data         $helperData
     * @param \Magento\Framework\App\ResourceConnection   $resource
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\App\Helper\Context       $context
     */
    public function __construct(
        \Plumrocket\PrivateSale\Helper\Data $helperData,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->_helperData = $helperData;
        $this->resource = $resource;
        $this->date = $date;
        parent::__construct($context);
    }

	/**
	 * Preview key
	 * @var string
	 */
    protected $previewKey = 'psPreviewMode';

    /**
     * Retrieve preview key
     * @return string
     */
    public function getPreviewKey()
    {
        return $this->previewKey;
    }

    /**
     * Checking preview accessible
     * If isset preview key and code is valid
     * @return boolean
     */
    public function isAllowPreview()
    {
        $request = $this->_request;

        if ($request->isXmlHttpRequest()
            && $request->getFullActionName() == 'privatesales_ajax_time'
            && $request->getParam('previewDate')
        ) {
            return true;
        }

        $ssd = $request->getParam($this->getPreviewKey());

        if (!$ssd) {
        	return false;
        }

        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        $data = $connection
            ->fetchAll(sprintf("SELECT `code` FROM %s WHERE `code` = %s AND `active_to` >= '%s'",
                $this->resource->getTableName('plumrocket_privatesale_preview_access'),
                $connection->quote($ssd),
                strftime('%F %T', $this->date->timestamp(time()))
            ));


        if ($data) {
            return true;
        }

        return false;
    }

    /**
     * Add privatesale attribute to select
     * @param $observers
     */
    public function addPrivatesaleAttributeToSelect($observers)
    {
    	if ($this->isAllowPreview()) {
    		$this->_helperData->addPrivatesaleAttributeToSelect($observers->getEvent()->getCategoryCollection());
    	}
    }

    /**
     * Retrieve preview time
     * @return int
     */
    public function getPreviewTime()
    {
       	return strtotime($this->getPreviewDate());
    }

    /**
     * Retrieve preview date
     * @return string
     */
    public function getPreviewDate()
    {
    	if (!$previewDate = $this->_request->getParam('previewDate')) {
            $previewDate = $this->date->gmtDate('m/d/Y');
        }

        return $previewDate;
    }
}