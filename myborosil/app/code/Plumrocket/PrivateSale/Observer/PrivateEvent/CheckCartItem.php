<?php

namespace Plumrocket\PrivateSale\Observer\PrivateEvent;


class CheckCartItem extends AbstractCheckProduct
{
    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getItem()->getProduct();

        if ($this->isProductPrivateEvent($product)) {
            $observer->getItem()->addErrorInfo(
                'privatsales',
                \Magento\CatalogInventory\Helper\Data::ERROR_QTY,
                __('We can\'t add this item to your shopping cart right now.')
            );
        }
    }
}
