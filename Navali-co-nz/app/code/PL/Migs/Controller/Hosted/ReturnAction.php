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
class ReturnAction extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \PL\Migs\Helper\Data
     */
    protected $migsHelper;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @var \Magento\Sales\Model\Order\Status\HistoryFactory
     */
    protected $orderHistoryFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $session;

    /**
     * @var \PL\Migs\Logger\Logger
     */
    protected $plLogger;

    /**
     * @var \PL\Migs\Model\Api\PaymentRequest
     */
    protected $paymentRequest;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $hosted;

    /**
     * ReturnAction constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \PL\Migs\Helper\Data $migsHelper
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Sales\Model\Order\Status\HistoryFactory $orderHistoryFactory
     * @param \Magento\Checkout\Model\Session $session
     * @param \PL\Migs\Logger\Logger $plLogger
     * @param \PL\Migs\Model\Api\PaymentRequest $paymentRequest
     * @param \PL\Migs\Model\Hosted $hosted
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \PL\Migs\Helper\Data $migsHelper,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Model\Order\Status\HistoryFactory $orderHistoryFactory,
        \Magento\Checkout\Model\Session $session,
        \PL\Migs\Logger\Logger $plLogger,
        \PL\Migs\Model\Api\PaymentRequest $paymentRequest,
        \PL\Migs\Model\Hosted $hosted,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->migsHelper = $migsHelper;
        $this->orderFactory = $orderFactory;
        $this->orderHistoryFactory = $orderHistoryFactory;
        $this->session = $session;
        $this->plLogger = $plLogger;
        $this->paymentRequest = $paymentRequest;
        $this->storeManager = $storeManager;
        $this->hosted =$hosted;
        parent::__construct($context);
    }

    public function execute()
    {
        $params = $this->getRequest()->getParams();
        if ($this->hosted->getConfigData('debug')) {
            $this->plLogger->debug(print_r($params, 1));
        }
        if (isset($params['vpc_SecureHash']) && $this->validateReceipt($params)) {
            if (isset($params['vpc_MerchTxnRef'])) {
                $incrementId = $params['vpc_MerchTxnRef'];
                $txnResponseCode = $params['vpc_TxnResponseCode'];
                $order = $this->getOrder($incrementId);
                if ($order->getId()) {
                    if ($txnResponseCode =='0' || $txnResponseCode == '00') {
                        $this->paymentRequest->success($order, $params);
                        $this->_redirect('checkout/onepage/success');
                    } else {
                        $this->paymentRequest->cancel($order, $params);
                        $this->messageManager->addError(__('You have cancelled the order. Please try again'));
                        $this->_redirect('checkout/cart');
                    }
                }
            }
        }
    }

    /**
     * @param $params
     * @return bool
     */
    public function validateReceipt($params)
    {
        $encryptor = $this->_objectManager->get('\Magento\Framework\Encryption\EncryptorInterface');
        $secure_secret = $encryptor->decrypt(
            $this->hosted->getConfigData('secure_secret')
        );
        ksort($params);
        $hashString='';
        foreach ($params as $key => $value) {
            if ($key != 'vpc_SecureHash' && $key != 'vpc_SecureHashType' && strlen($value) > 0) {
                $hashString .= $key . "=" . $value . "&";
            }
        }
        return strtoupper($params['vpc_SecureHash']) == strtoupper(hash_hmac('SHA256', rtrim($hashString, "&"), pack('H*', $secure_secret)));
    }

    /**
     * @param $incrementId
     * @return \Magento\Sales\Model\Order
     */
    protected function getOrder($incrementId)
    {
        if (!$this->order) {
            $this->order = $this->orderFactory->create()->loadByIncrementId($incrementId);
        }
        return $this->order;
    }
}
