<?php

namespace Plumrocket\PrivateSale\Observer\PrivateEvent;


class CheckProduct extends AbstractCheckProduct
{
    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $this->registry->registry('current_product');

        if ($this->isProductPrivateEvent($product)) {
            $redirectUrl = $this->privateEvent->getProductRedirectUrl($product);

            $data = $observer->getEvent()->getData();
            $data['controller_action']->getResponse()->setRedirect($redirectUrl);
        }
    }
}
