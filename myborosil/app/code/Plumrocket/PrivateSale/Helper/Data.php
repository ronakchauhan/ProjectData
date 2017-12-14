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

class Data extends Main
{
    /**
     * Configuration path to enable module
     */
    const MODULE_ENABLED_PATH = 'general/enabled';

    /**
     * Config section id
     * @var string
     */
    protected $_configSectionId = 'prprivatesale';

    /**
     * Children categories
     * @var array
     */
    protected $childrens;

    /**
     * Need for disable module
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * Config
     * @var \Magento\Config\Model\Config
     */
    protected $config;

    /**
     * Session
     * @var \Magento\Customer\Model\Session
     */
    protected $session;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\App\Helper\Context     $context
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param \Magento\Config\Model\Config              $config
     * @param \Magento\Customer\Model\Session           $session
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Config\Model\Config $config,
        \Magento\Customer\Model\Session $session
    ) {
        parent::__construct($objectManager, $context);
        $this->resourceConnection   = $resourceConnection;
        $this->config               = $config;
        $this->session              = $session;
    }

    /**
     * Is module enabled
     * @param  int $store store id
     * @return boolean
     */
    public function moduleEnabled($store = null)
    {
        return (bool)$this->getConfig($this->_configSectionId . '/' . self::MODULE_ENABLED_PATH);
    }

    /**
     * Retrieve children categories
     * @param  \Magento\Catalog\Model\Category $category
     * @param  string $type
     * @return array
     */
    public function getChildrenCategories($category, $type = 'events')
    {
        $ids = $type == 'events' ? $category->getChildren() : $category->getAllChildren();
        $ids = explode(',', $ids);

        $catKey = array_search($category->getId(), $ids);
        if ($catKey !== false) {
            unset($ids[$catKey]);
        }

        $_categories = $category->getCollection();
        $_categories
            ->addAttributeToSelect('*')
            ->setOrder('position', \Magento\Framework\DB\Select::SQL_ASC)
            // ->addUrlRewriteToResult()
            ->addAttributeToFilter('is_active', 1)
            ->addIdFilter($ids);

        $_categories->setFlag('dont_remove_not_active');
        $this->addPrivatesaleAttributeToSelect($_categories);
        $_categories->addAttributeToSelect('image');

        $this->childrens[$type][$category->getId()] = $_categories;

        return $this->childrens[$type][$category->getId()];
    }

    /**
     * Disable extension if needed
     * @return void
     */
    public function disableExtension()
    {
        $connection = $this->resourceConnection->getConnection('core_write');
        $connection->delete($this->resourceConnection->getTableName('core_config_data'),
            [$connection->quoteInto('path = ?', $this->_configSectionId.'/general/enabled')]
        );

        $this->config->setDataByPath($this->_configSectionId.'/general/enabled', 0);
        $this->config->save();
    }

    /**
     * Add privatesale attribute to select
     * @param \Magento\Catalog\Model\ResourceModel\Category\Collection $collection
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    public function addPrivatesaleAttributeToSelect($collection)
    {
        $collection->addAttributeToSelect('privatesale_date_start')
            ->addAttributeToSelect('privatesale_date_end')
            ->addAttributeToSelect('privatesale_event_image')
            ->addAttributeToSelect('privatesale_email_image')
            ->addAttributeToSelect('privatesale_event_end');

        return $collection;
    }

    /**
     * Add end date filter to collection
     * There in magento is bug with datetime attribute, when i try add is null condition
     * @param \Magento\Catalog\Model\ResourceModel\Category\Collection $collection
     * @param string $time
     * @return mixed
     * @throws \Exception
     */
    public function addEndDateFilter($collection, $time)
    {
        if (is_string($time)) {
            $time = strtotime($time);
            if (!$time) {
                throw new \Exception('Incorrect time format. Time must be timestamp or has correct date format');
            }
        }

        $collection->addAttributeToSelect('privatesale_date_end');
        foreach ($collection as $key => $item) {
            $dateEnd = $item->getPrivatesaleDateEnd();

            if (
                $dateEnd === null
                || strtotime($dateEnd) > $time
            ) {
                continue;
            }

            $collection->removeItemByKey($key);
        }

        /*
         * In future it can be fixed and optimized
        $collection
            ->addAttributeToFilter('privatesale_date_end',
                [
                    ['gteq' => $currentDate],
                    ['null' =>  true],
                    ['eq' =>  '0000-00-00 00:00:00'],
                    ['eq' =>  ''],
                ]
            );*/
        return $collection;
    }

    /**
     * Retrieve true if product is private event and customer is not logged in
     * @param  \Magento\Catalog\Model\Product $product
     * @return boolean
     */
    public function isProductLocked($product)
    {
        return !$this->session->getCustomerGroupId() && $product->getPrivatesalePrivateEvent() == 2;
    }
}
