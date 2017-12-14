<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\CheckoutView\Block\Onepage;

use Magento\Customer\Model\Context;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address\Renderer;

/**
 * One page checkout success page
 */
class Success extends \Magento\Checkout\Block\Onepage\Success
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $_orderConfig;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    protected $addressRenderer;

    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    protected $imageBuilder;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Sales\Model\Order $orderFactory,
        Renderer $addressRenderer,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        array $data = []
    ) {
        parent::__construct($context, $checkoutSession, $orderConfig, $httpContext, $data);
        $this->_checkoutSession = $checkoutSession;
        $this->_orderConfig = $orderConfig;
        $this->_isScopePrivate = true;
        $this->httpContext = $httpContext;
        $this->_orderFactory = $orderFactory;
        $this->addressRenderer = $addressRenderer;
        $this->imageBuilder = $imageBuilder;
    }

    public function getOrderData()
    {
        $order = $this->_orderFactory->load($this->getOrderId());

        return $order;
    }

    public function getShippingAddress(\Magento\Sales\Model\Order $order)
    {
        return $this->addressRenderer->format($order->getShippingAddress(), 'html');
    }

    public function getGuestCustomerName(\Magento\Sales\Model\Order $order)
    {
        return $order->getBillingAddress()->getFirstName();
    }


    /**
     * Retrieve product image
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }
}