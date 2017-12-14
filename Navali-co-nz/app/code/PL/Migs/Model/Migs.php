<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Migs\Model;

class Migs extends \Magento\Payment\Model\Method\Cc
{
    const METHOD_CODE = 'migs';

    // Credit Card URLs
    const CC_URL_LIVE = 'https://migs.mastercard.com.au/vpcdps';

    const STATUS_APPROVED = 'Approved';

    const PAYMENT_ACTION_AUTH_CAPTURE = 'authorize_capture';

    const PAYMENT_ACTION_AUTH = 'authorize';

    protected $_code = self::METHOD_CODE;

    /**
     * @var bool
     */
    protected $_isGateway = true;

    /**
     * @var bool
     */
    protected $_canCapture = true;

    /**
     * @var \PL\Migs\Helper\Data
     */
    protected $migsHelper;

    /**
     * @var \PL\Migs\Logger\PLLogger
     */
    protected $plLogger;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;


    public function __construct(
        \PL\Migs\Helper\Data $migsHelper,
        \PL\Migs\Logger\Logger $plLogger,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $moduleList,
            $localeDate,
            $resource,
            $resourceCollection,
            $data
        );
        $this->migsHelper = $migsHelper;
        $this->plLogger = $plLogger;
        $this->storeManager = $storeManager;
    }

    /**
     * get Geteway Url
     * @return string
     */
    public function getGatewayUrl()
    {
        return self::CC_URL_LIVE;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function validate()
    {

        /** @noinspection PhpDeprecationInspection */
        parent::validate();
        $paymentInfo = $this->getInfoInstance();
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        if ($paymentInfo instanceof \Magento\Sales\Model\Order\Payment) {
            $paymentInfo->getOrder()->getBaseCurrencyCode();
        } else {
            $paymentInfo->getQuote()->getBaseCurrencyCode();
        }
        return $this;
    }

    /*public function assignData(\Magento\Framework\DataObject $data)
    {
        $additionalData = $data->getData(\Magento\Quote\Api\Data\PaymentInterface::KEY_ADDITIONAL_DATA);
        if (!is_object($additionalData)) {
            $additionalData = new \Magento\Framework\DataObject($additionalData ?: []);
        }
        $info = $this->getInfoInstance();
        $info->addData(
            [
                'cc_type' => $additionalData->getCcType(),
                'cc_owner' => $additionalData->getCcOwner(),
                'cc_last_4' => substr($additionalData->getCcNumber(), -4),
                'cc_number' => $additionalData->getCcNumber(),
                'cc_cid' => $additionalData->getCcCid(),
                'cc_exp_month' => $additionalData->getCcExpMonth(),
                'cc_exp_year' => $additionalData->getCcExpYear(),
                'cc_ss_issue' => $additionalData->getCcSsIssue(),
                'cc_ss_start_month' => $additionalData->getCcSsStartMonth(),
                'cc_ss_start_year' => $additionalData->getCcSsStartYear()
            ]
        );

        return $this;
    }*/

    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $this->setAmount($amount)->setPayment($payment);
        $result = $this->processRequest($payment);
        $errorMessage = false;
        try {
            if ($result['vpc_TxnResponseCode'] == "0") {
                $payment->setStatus(self::STATUS_APPROVED)
                    ->setLastTransId($result['vpc_TransactionNo'])
                    ->setTransactionId($result['vpc_TransactionNo'])
                    ->setParentTransactionId($result['vpc_TransactionNo'])
                    ->setIsTransactionClosed(1);
            } else {
                $errorMessage = __('Gateway Error: %1', $result['vpc_Message']);
            }
        } catch(\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
        }
        if ($errorMessage) {
            throw new \Magento\Framework\Exception\LocalizedException($errorMessage);
        }

        return $this;
    }

    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @return array|bool
     */
    protected function processRequest(\Magento\Payment\Model\InfoInterface $payment)
    {

        $date_expiry = substr($payment->getCcExpYear(), 2, 2)
            . str_pad($payment->getCcExpMonth(), 2, '0', STR_PAD_LEFT);
        $amount = $this->getAmount() * 100;
        if ($payment->getOrder()->getBaseCurrencyCode() == 'JPY' ||
            $payment->getOrder()->getBaseCurrencyCode() == 'ITL' ||
            $payment->getOrder()->getBaseCurrencyCode() == 'GRD'
        ) {
            $amount = $amount / 100;
        }
        $request = [
            'vpc_Version' => '1',
            'vpc_Command' => 'pay',
            'vpc_MerchTxnRef' => $payment->getOrder()->getIncrementId(),
            'vpc_Merchant' => $this->getConfigData('merchant_id'),
            'vpc_OrderInfo' => $payment->getOrder()->getIncrementId(),
            'vpc_CardNum' => $payment->getCcNumber(),
            'vpc_CardExp' => $date_expiry,
            'vpc_CardSecurityCode' => $payment->getCcCid(),
            'vpc_AccessCode' => $this->getConfigData('access_code'),
            'vpc_Amount' => $amount,
            //'vpc_CSCLevel' => 'N',
            'vpc_TicketNo' => ''
        ];

        $postRequestData = '';
        $amp = '';
        foreach ($request as $key => $value) {
            if (!empty($value)) {
                $postRequestData .= $amp . urlencode($key) . '=' . urlencode($value);
                $amp = '&';
            }
        }

        $curl = new \Magento\Framework\HTTP\Adapter\Curl();
        $curl->setConfig([
            'verifypeer'=> $this->getConfigData('ssl_enabled'),
            'verifyhost'=> 2,
            'timeout'=>60
        ]);
        $curl->write(
            \Zend_Http_Client::POST,
            $this->getGatewayUrl(),
            '1.1',
            [],
            $postRequestData
        );
        $response = $curl->read();
        $response = preg_split('/^\r?$/m', $response, 2);
        $response = trim($response[1]);
        $result = [];
        $pieces = explode('&', $response);
        foreach ($pieces as $piece) {
            $tokens = explode('=', $piece);
            $result[$tokens[0]] = $tokens[1];
        }
        if ($this->getConfigData('debug')) {
            $this->plLogger->debug(print_r($result, 1));
        }
        return $result;
    }
}
