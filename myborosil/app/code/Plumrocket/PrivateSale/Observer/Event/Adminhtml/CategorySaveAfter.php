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


class CategorySaveAfter extends EventObserver
{

	/**
	 * Save category products to indexer table
	 * @param  \Magento\Framework\Event\Observer $observer
	 * @return void
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$store = $this->request->getParam('store');
        if (!$store) {
            $store = 0;
        }

        $category = $observer->getEvent()->getCategory();
        $indexer = $this->indexerFactory->create()->getCollection()
            ->addFieldToFilter('store_id', $store)
            ->addFieldToFilter('category_id', $category->getId());

        $indexer->walk('delete');

        if ($category->getPrivatesaleDateStart()) {

            $eventModel = $this->event;

            $products = $eventModel->getCategoryProducts($category);

            foreach ($products as $product) {

                $eventStartAction = $eventModel->getProductBeforeEventStart($product, $category);
                $eventStartActionUseConfig = $eventModel->getUseConfig();

                $eventEndAction = $eventModel->getEventEndAction($category, $product);
                $eventEndActionUseConfig = $eventModel->getUseConfig();

                $_data = [
                    'start_date' => $category->getPrivatesaleDateStart(),
                    'end_date' => $category->getPrivatesaleDateEnd(),
                    'product_id' => $product->getId(),
                    'category_id'  => $category->getId(),
                    'store_id' => $store,
                    'event_start_action' => (int)$eventStartAction,
                    'event_start_action_use_config' => (int)$eventStartActionUseConfig,
                    'event_end_action' => (int)$eventEndAction,
                    'event_end_action_use_config' => (int)$eventEndActionUseConfig
                ];

                $this->indexerFactory->create()->setData($_data)
                    ->save();
            }
        }
	}
}
