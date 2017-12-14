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


namespace Plumrocket\PrivateSale\Observer\Event;

class ProductCollectionLoadBefore extends EventObserver
{
    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($observer->getCollection() instanceof \Magento\Catalog\Model\ResourceModel\Product\Collection) {
            if ($this->_allowProcess()) {
                $collection = $observer->getEvent()->getCollection();

                $storeId = $this->event->getStoreId();
                $resource = $collection->getResource();

                $currentDate = $this->event->getCurrentDate(false);


                $condition = "
                    (
                        psd.start_date < '$currentDate'
                        OR
                        psd.event_start_action = '" . \Plumrocket\PrivateSale\Model\Config\Source\Beforeeventstart::DISPLAY_YES . "'
                    )
                    AND (
                        psd.end_date > '$currentDate'
                        OR
                        psd.event_end_action <> '" . \Plumrocket\PrivateSale\Model\Config\Source\Eventend::NOT_FOUND . "'
                    )
                ";

                $subquery = '
                SELECT product_id, store_id, active FROM (
                    SELECT  product_id, store_id, IF (' . $condition . ', NULL, 0) as active
                    FROM  ' . $resource->getTable('plumrocket_privatesale_product_indexer') . ' as psd
                    ORDER BY active DESC
                ) psd2 group by psd2.product_id';


                $collection->getSelect()->joinLeft(
                    new \Zend_Db_Expr('(' . $subquery . ')'),
                    "e.entity_id = t.product_id AND (t.store_id = '$storeId' OR t.store_id = '0')",
                    array('active')
                );

                $collection->getSelect()->where('t.active IS NULL');
            }
        }
    }
}
