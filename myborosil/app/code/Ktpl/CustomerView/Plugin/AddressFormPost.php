<?php

namespace Ktpl\CustomerView\Plugin;

class AddressFormPost
{
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $_redirect;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->_redirect = $redirect;
        $this->_objectManager = $objectManager;
        $this->_customerSession = $customerSession;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function afterExecute(
        \Magento\Customer\Controller\Address\FormPost $subject
        )
    {
        $addressFormData = $this->_getSession()->getAddressFormData();
        if(!isset($addressFormData))
        {
            $url = $this->_buildUrl('customerview/account/index', ['_secure' => true]);
            return $this->resultRedirectFactory->create()->setUrl($this->_redirect->success($url));
        }       
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    protected function _buildUrl($route = '', $params = [])
    {
        /** @var \Magento\Framework\UrlInterface $urlBuilder */
        $urlBuilder = $this->_objectManager->create('Magento\Framework\UrlInterface');
        return $urlBuilder->getUrl($route, $params);
    }

    /**
     * Retrieve customer session object
     *
     * @return \Magento\Customer\Model\Session
     */
    protected function _getSession()
    {
        return $this->_customerSession;
    }
}
