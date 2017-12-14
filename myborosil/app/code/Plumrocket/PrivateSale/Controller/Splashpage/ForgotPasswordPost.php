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

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SecurityViolationException;

class ForgotPasswordPost extends \Magento\Customer\Controller\Account\ForgotPasswordPost
{
    /**
     * Json Factory
     * @var  \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * Json helper
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param Escaper $escaper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement,
        \Magento\Framework\Escaper $escaper
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context, $customerSession, $customerAccountManagement, $escaper);
    }

    /**
     * Forgot customer password action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
  	public function execute()
    {
        $data = $this->jsonHelper->jsonDecode($this->getRequest()->getContent());


        if (!empty($data['email'])) {
            $email = $data['email'];
            $response = [
                'errors' => false,
                'message' => $this->getSuccessMessage($email)
            ];
            if (!\Zend_Validate::is($email, 'EmailAddress')) {
                $this->session->setForgottenEmail($email);

                $response = [
                    'errors' => true,
                    'message' => __('Please correct the email address.')
                ];
            }

            try {
                $this->customerAccountManagement->initiatePasswordReset(
                    $email,
                    \Magento\Customer\Model\AccountManagement::EMAIL_RESET
                );
            } catch (NoSuchEntityException $exception) {
                // Do nothing, we don't want anyone to use this action to determine which email accounts are registered.
            } catch (SecurityViolationException $exception) {
                $response = [
                    'errors' => true,
                    'message' => $exception->getMessage()
                ];
            } catch (\Exception $exception) {
                $response = [
                    'errors' => true,
                    'message' => __('We\'re unable to send the password reset email.')
                ];
            }
        } else {
            $this->messageManager->addErrorMessage(__('Please enter your email.'));
            $response = [
                'errors' => true,
                'message' => __('Please enter your email.')
            ];
        }
        $resultJson = $this->jsonFactory->create();
        return $resultJson->setData($response);
    }
}
