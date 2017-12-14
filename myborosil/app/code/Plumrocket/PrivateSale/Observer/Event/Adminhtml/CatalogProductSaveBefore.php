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

use Plumrocket\PrivateSale\Model\Attribute\Source\PrivateEvent;

class CatalogProductSaveBefore extends EventObserver
{

    /**
     * {@inheritdoc}
     */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$product = $observer->getEvent()->getProduct();
        if (
            $product->getPrivatesalePrivateEvent() == PrivateEvent::PRIVATE_YES
            && !$product->getPrivatesalePrivateEventFlag()
        ) {
            $product->setPrivatesalePrivateEventFlag(1);
        } elseif (
            $product->getPrivatesalePrivateEvent() == PrivateEvent::PRIVATE_NO
            && $product->getPrivatesalePrivateEventFlag()
        ) {
            $product->setPrivatesalePrivateEventFlag(0);
        } elseif ($product->getPrivatesalePrivateEvent() == PrivateEvent::USE_PARENT) {
            $categories = $this->categoryFactory->create()->getCollection()
                ->addFieldToFilter('entity_id', array('in' => $product->getCategoryIds()))
                ->addAttributeToFilter('privatesale_private_event_flag', 1);

            if ($categories->count()) {
                $product->setPrivatesalePrivateEventFlag(1);
            } else {
                $product->setPrivatesalePrivateEventFlag(0);
            }
        }
	}
}
