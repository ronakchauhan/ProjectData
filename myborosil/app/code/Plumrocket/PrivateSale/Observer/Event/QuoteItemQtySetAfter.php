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

class QuoteItemQtySetAfter extends EventObserver
{
    /**
     * {@inheritdoc}
     */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		if ($this->_allowProcess()) {
            $item = $observer->getEvent()->getItem();

            if ($this->canDisableProduct($item->getProduct())) {
                $item->setHasError(true)
                    ->setMessage(
                        __('Sale was closed for this product.')
                    );
                $item->getQuote()->setHasError(true)
                    ->addMessage(
                        __('Some products are not salable anymore.')
                    );
            }
        }
	}

    /**
     * Can disable product
     * @param  Magento\Catalog\Model\Product $product
     * @return boolean
     */
    private function canDisableProduct($product)
    {
        if (!$product->getId() || !$product instanceof \Magento\Catalog\Model\Product) {
            throw new \Exception('Product is missing');
        }

        if ($product->getPrivatesaleExpiredFlag() === true) {
            return true;
        }

        $action = $this->event->checkProduct($product);

        return $action == \Plumrocket\PrivateSale\Model\Config\Source\Eventend::NOT_FOUND
            || $action == \Plumrocket\PrivateSale\Model\Config\Source\Eventend::DISABLE_ADD_TO_CART;
    }
}
