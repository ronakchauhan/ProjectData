<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Migs\Controller\Hosted;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
class Redirect extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Quote\Model\Quote
     */
    protected $quote = false;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * Return checkout session object
     *
     * @return \Magento\Checkout\Model\Session
     */
    protected function getCheckoutSession()
    {
        return $this->checkoutSession;
    }

    /**
     * Set redirect
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }

    /**
     * Get order object
     *
     * @return \Magento\Sales\Model\Order
     */
    protected function getOrder()
    {
        if (!$this->order) {
            $incrementId = $this->getCheckout()->getLastRealOrderId();
            $this->orderFactory = $this->_objectManager->get('Magento\Sales\Model\OrderFactory');
            $this->order = $this->orderFactory->create()->loadByIncrementId($incrementId);
        }
        return $this->order;
    }

    /**
     * @return \Magento\Checkout\Model\Session
     */
    protected function getCheckout()
    {
        return $this->_objectManager->get('Magento\Checkout\Model\Session');
    }

    /**
     * @return mixed
     */
    protected function getQuote()
    {
        return $this->_objectManager->get('Magento\Quote\Model\Quote');
    }

    /**
     * @return mixed
     */
    protected function getQuoteManagement()
    {
        return $this->_objectManager->get('\Magento\Quote\Model\QuoteManagement');
    }
}
