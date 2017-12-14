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

namespace Plumrocket\PrivateSale\Observer\Event\Adminhtml;


class CatalogProductSaveAfter extends EventObserver
{

    /**
     * {@inheritdoc}
     */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$store = $this->request->getParam('store');
        $product = $observer->getEvent()->getProduct();

        $indexer = $this->indexerFactory->create()->getCollection()
            ->addFieldToFilter('product_id', $product->getId())
            ->addFieldToFilter('store_id', $store)
            ->addFieldToFilter('category_id', 0)
            ->setPageSize(1)
            ->getFirstItem();

        if ($indexer->getId()) {
            $indexer->delete();
        }


        if ($product->getPrivatesaleDateStart()) {

            $eventModel = $this->event;

            $eventStartAction = $eventModel->getProductBeforeEventStart($product);
            $eventStartActionUseConfig = $eventModel->getUseConfig();

            $eventEndAction = $eventModel->getEventEndAction(null, $product);
            $eventEndActionUseConfig = $eventModel->getUseConfig();

            $data = array(
                'start_date' => $product->getPrivatesaleDateStart(),
                'end_date' => $product->getPrivatesaleDateEnd() !== null ? $product->getPrivatesaleDateEnd() : '',
                'product_id' => $product->getId(),
                'category_id'  => null,
                'event_start_action' => (int)$eventStartAction,
                'event_start_action_use_config' => (int)$eventStartActionUseConfig,
                'event_end_action' => (int)$eventEndAction,
                'event_end_action_use_config' => (int)$eventEndActionUseConfig
            );

            // if ($store) {
                $data['store_id'] = $store;
            // }

            $this->indexerFactory->create()->setData($data)
                ->save();
        }
	}
}
