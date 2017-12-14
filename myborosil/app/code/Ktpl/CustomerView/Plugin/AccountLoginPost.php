<?php

namespace Ktpl\CustomerView\Plugin;

use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Framework\App\Action\Context;

class AccountLoginPost
{
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var AccountRedirect
     */
    protected $accountRedirect;

    /**
     * @var PageFactory
     */
    protected $resultRedirectFactory;

    /**
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        AccountRedirect $accountRedirect,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->accountRedirect = $accountRedirect;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function afterExecute(
        \Magento\Customer\Controller\Account\Index $subject,
        $result
        )
    {
        $errorMessages = $this->messageManager->getMessages()->getErrors();
        
        if(!count($errorMessages))
        {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customerview/account');
            return $resultRedirect;
        }

        return $result;
    }
}