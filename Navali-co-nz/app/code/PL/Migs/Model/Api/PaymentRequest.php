<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Migs\Model\Api;

use Magento\Framework\DataObject;

class PaymentRequest extends DataObject
{
    const DEFAULT_STATUS_NEW = 'pending';

    const DEFAULT_STATUS_PENDING_PAYMENT = 'pending_payment';

    const DEFAULT_STATUS_PROCESSING = 'processing';

    /**
     * @var \PL\Migs\Helper\Data
     */
    protected $migsHelper;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $appState;

    /**
     * @var \PL\Migs\Logger\Logger
     */
    protected $plLogger;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $orderSender;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\InvoiceSender
     */
    protected $invoiceSender;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $session;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * PaymentRequest constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \PL\Migs\Helper\Data $migsHelper
     * @param \PL\Migs\Logger\Logger $plLogger
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender
     * @param \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender
     * @param \Magento\Checkout\Model\Session $session
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \PL\Migs\Helper\Data $migsHelper,
        \PL\Migs\Logger\Logger $plLogger,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender,
        \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender,
        \Magento\Checkout\Model\Session $session,
        array $data = []
    ) {
        parent::__construct($data);
        $this->migsHelper = $migsHelper;
        $this->plLogger = $plLogger;
        $this->orderSender = $orderSender;
        $this->invoiceSender = $invoiceSender;
        $this->session = $session;
        $this->jsonHelper = $jsonHelper;
    }


    /**
     * @param $order
     * @param array $responseData
     */
    public function success($order, $responseData = [])
    {
        if ($order->getId()) {
            $additionalData = $this->jsonHelper->jsonEncode($responseData);
            $order->getPayment()->setTransactionId($responseData['vpc_TransactionNo']);
            $order->getPayment()->setLastTransId($responseData['vpc_TransactionNo']);
            $order->getPayment()->setAdditionalInformation('payment_additional_info', $additionalData);
            $order->setStatus(\Magento\Sales\Model\Order::STATE_PROCESSING);
            $note = __('Approved the payment online. Transaction ID: "%1"', $responseData['vpc_TransactionNo']);
            $order->setState(\Magento\Sales\Model\Order::STATE_PROCESSING);
            $order->addStatusHistoryComment($note);
            $order->save();
            $this->orderSender->send($order);
            if (!$order->hasInvoices() && $order->canInvoice()) {
                $invoice = $order->prepareInvoice();
                if ($invoice->getTotalQty() > 0) {
                    $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
                    $invoice->setTransactionId($order->getPayment()->getTransactionId());
                    $invoice->register();
                    $invoice->addComment(__('Automatic invoice.'), false);
                    $invoice->save();
                    $this->invoiceSender->send($invoice);
                }
            }
        }
    }

    /**
     * @param $order
     * @param array $responseData
     */
    public function cancel($order, $responseData = [])
    {
        if ($order->getId()) {
            $additionalData = $this->jsonHelper->jsonEncode($responseData);
            $order->getPayment()->setTransactionId($responseData['vpc_TransactionNo']);
            $order->getPayment()->setLastTransId($responseData['vpc_TransactionNo']);
            $order->getPayment()->setAdditionalInformation('payment_additional_info', $additionalData);
            $note = __('Payment Gateway was declined. Transaction ID: "%1"', $responseData['vpc_TransactionNo']);
            $order->setState(\Magento\Sales\Model\Order::STATE_CANCELED);
            $order->setStatus(\Magento\Sales\Model\Order::STATE_CANCELED);
            $order->setCustomerNoteNotify(false);
            $order->addStatusHistoryComment($note);
            $order->cancel()->save();
        }
    }
}
