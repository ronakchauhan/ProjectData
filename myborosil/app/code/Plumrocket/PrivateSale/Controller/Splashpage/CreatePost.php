<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket Private Sales and Flash Sales v4.x.x
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\PrivateSale\Controller\Splashpage;

class CreatePost extends \Magento\Customer\Controller\Account\CreatePost
{
    /**
     * Json Helper
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $helperJson;

    /**
     * Stdlib Parameters
     * @var \Zend\Stdlib\Parameters
     */
    protected $parameters;

    /**
     * JsonFactory
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonResultFactory;

    /**
     * Splashpage Factory
     * @var \Plumrocket\PrivateSale\Model\SplashpageFactory
     */
    protected $splashpageFactory;

    /**
     * CreatePost constructor.
     *
     * @param \Magento\Framework\App\Action\Context               $context
     * @param \Magento\Customer\Model\Session                     $customerSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface  $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface          $storeManager
     * @param \Magento\Customer\Api\AccountManagementInterface    $accountManagement
     * @param \Magento\Customer\Helper\Address                    $addressHelper
     * @param \Magento\Framework\UrlFactory                       $urlFactory
     * @param \Magento\Customer\Model\Metadata\FormFactory        $formFactory
     * @param \Magento\Newsletter\Model\SubscriberFactory         $subscriberFactory
     * @param \Magento\Customer\Api\Data\RegionInterfaceFactory   $regionDataFactory
     * @param \Magento\Customer\Api\Data\AddressInterfaceFactory  $addressDataFactory
     * @param \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory
     * @param \Magento\Customer\Model\Url                         $customerUrl
     * @param \Magento\Customer\Model\Registration                $registration
     * @param \Magento\Framework\Escaper                          $escaper
     * @param \Magento\Customer\Model\CustomerExtractor           $customerExtractor
     * @param \Magento\Framework\Api\DataObjectHelper             $dataObjectHelper
     * @param \Magento\Customer\Model\Account\Redirect            $accountRedirect
     * @param \Magento\Framework\Json\Helper\Data                 $helperJson
     * @param \Zend\Stdlib\Parameters                             $parameters
     * @param \Magento\Framework\Controller\Result\JsonFactory    $jsonResultFactory
     * @param \Plumrocket\PrivateSale\Model\SplashpageFactory     $splashpageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Customer\Helper\Address $addressHelper,
        \Magento\Framework\UrlFactory $urlFactory,
        \Magento\Customer\Model\Metadata\FormFactory $formFactory,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Customer\Api\Data\RegionInterfaceFactory $regionDataFactory,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\Customer\Model\Registration $registration,
        \Magento\Framework\Escaper $escaper,
        \Magento\Customer\Model\CustomerExtractor $customerExtractor,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Customer\Model\Account\Redirect $accountRedirect,
        \Magento\Framework\Json\Helper\Data $helperJson,
        \Zend\Stdlib\Parameters $parameters,
        \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory,
        \Plumrocket\PrivateSale\Model\SplashpageFactory $splashpageFactory
    ) {
        parent::__construct($context,
            $customerSession,
            $scopeConfig,
            $storeManager,
            $accountManagement,
            $addressHelper,
            $urlFactory,
            $formFactory,
            $subscriberFactory,
            $regionDataFactory,
            $addressDataFactory,
            $customerDataFactory,
            $customerUrl,
            $registration,
            $escaper,
            $customerExtractor,
            $dataObjectHelper,
            $accountRedirect
        );
        $this->helperJson = $helperJson;
        $this->parameters = $parameters;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->splashpageFactory = $splashpageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $data = $this->helperJson->jsonDecode($this->getRequest()->getContent());

        $parameters = $this->parameters->create([$data]);
        $parameters->fromArray($data);

        $this->getRequest()->setPost($parameters);

        $result = parent::execute();

        $response = [
            'errors' => false,
            'message' => ''
        ];

        $message = $this->messageManager->getMessages()->getLastAddedMessage();
        $this->messageManager->getMessages()->clear();

        if ($message) {
            if ($message->getType() == \Magento\Framework\Message\MessageInterface::TYPE_SUCCESS) {
                $response = [
                    'errors' => false,
                    'message' => $message->getText()
                ];
            } else {
                $response = [
                    'errors' => true,
                    'message' => $message->getText()
                ];
            }
        }

        if ($this->splashpageFactory->create()->getData('enabled_launching_soon')) {
            $this->session->logout();
        }

        return $this->jsonResultFactory->create()->setData($response);
    }
}
