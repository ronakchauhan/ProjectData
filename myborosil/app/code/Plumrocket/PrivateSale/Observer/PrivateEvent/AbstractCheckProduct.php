<?php

namespace Plumrocket\PrivateSale\Observer\PrivateEvent;


use Magento\Framework\Event\ObserverInterface;

abstract class AbstractCheckProduct implements ObserverInterface
{
    /**
     * Private event
     * @var Plumrocket\PrivateSale\Model\PrivateEvent
     */
    protected $privateEvent;

    /**
     * Product factory
     * @var Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * Registry
     * @var Magento\Framework\Registry
     */
    protected $registry;    

    /**
     * Constructor
     * @param \Plumrocket\PrivateSale\Model\PrivateEvent $privateEvent
     * @param \Magento\Catalog\Model\ProductFactory      $productFactory
     * @param \Magento\Framework\Registry                $registry
     */
    public function __construct(
        \Plumrocket\PrivateSale\Model\PrivateEvent $privateEvent,
        \Magento\Catalog\Model\ProductFactory  $productFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->privateEvent = $privateEvent;
        $this->registry = $registry;
        $this->productFactory = $productFactory;
    }

    /**
     * Check if product is private event
     * @param  \Magento\Catalog\Model\Product  $product
     * @return boolean
     */
    protected function isProductPrivateEvent($product)
    {
        if (!$product || !$product->getId()) {
            return false;
        }

        return $this->privateEvent->isProductPrivateEvent($product);
    }

}
