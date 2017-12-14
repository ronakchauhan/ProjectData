<?php

namespace Plumrocket\PrivateSale\Observer\PrivateEvent;


class CheckCompareProduct extends AbstractCheckProduct
{
    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $controller = $observer->getEvent()->getData('controller_action');

        $product = $this->productFactory->create()->load(
            $controller->getRequest()->getParam('product')
        );


        if ($this->isProductPrivateEvent($product)) {
            $redirectUrl = $this->privateEvent->getProductRedirectUrl($product);
            $controller->getResponse()->setRedirect($redirectUrl, 301);
            $controller->getResponse()->sendResponse();
            $controller->getRequest()->setDispatched(true);
            $controller->setFlag('', \Magento\Framework\App\ActionInterface::FLAG_NO_DISPATCH, true);
        }
    }
}
