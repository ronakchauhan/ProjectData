<?php

namespace Ktpl\CustomerView\Plugin;

class AddressDelete
{
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
    ) {
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function afterExecute(
        \Magento\Customer\Controller\Address\Delete $subject
        )
    {
        return $this->resultRedirectFactory->create()->setPath('customerview/account');
    }
}
