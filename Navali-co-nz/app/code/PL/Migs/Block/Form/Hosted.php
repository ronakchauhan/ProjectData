<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Migs\Block\Form;

use Symfony\Component\Config\Definition\Exception\Exception;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
class Hosted extends \Magento\Payment\Block\Form
{
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var  \Magento\Checkout\Model\Order
     */
    protected $order;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data = []
    ) {
        $this->orderFactory = $orderFactory;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context, $data);
        $this->getOrder();
    }

    /**
     * @return $this
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getFormUrl()
    {
        $result = "";
        try {
            $order = $this->order;
            if ($order->getPayment()) {
                $result = $this->order->getPayment()->getMethodInstance()->getFormUrl();
            }
        } catch (Exception $e) {
            // do nothing for now
            throw($e);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getFormFields()
    {
        $result = [];
        try {
            if ($this->order->getPayment()) {
                $result = $this->order->getPayment()->getMethodInstance()->getFormFields();
            }
        } catch (Exception $e) {
            // do nothing for now
        }
        return $result;
    }

    /**
     * @return \Magento\Checkout\Model\Order|\Magento\Sales\Model\Order
     */
    protected function getOrder()
    {
        if (!$this->order) {
            $incrementId = $this->getCheckout()->getLastRealOrderId();
            $this->order = $this->orderFactory->create()->loadByIncrementId($incrementId);
        }
        return $this->order;
    }

    /**
     * @return \Magento\Checkout\Model\Session
     */
    protected function getCheckout()
    {
        return $this->checkoutSession;
    }
}
