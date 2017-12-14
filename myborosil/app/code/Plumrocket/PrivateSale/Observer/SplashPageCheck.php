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
namespace Plumrocket\PrivateSale\Observer;

use Magento\Framework\Event\ObserverInterface;

use \Plumrocket\PrivateSale\Model\Config\Source\Splashpagestatus;

class SplashPageCheck implements ObserverInterface
{

    /**
     * Data helper
     * @var \Plumrocket\PrivateSale\Helper\Data
     */
    protected $helper;

    /**
     * Customer helper
     * @var \Magento\Customer\Model\Url
     */
    protected $customerUrl;

    /**
     * Preview helper
     * @var \Plumrocket\PrivateSale\Helper\Preview
     */
    protected $previewHelper;

    /**
     * Customer Session
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * Splashpage
     * @var \Plumrocket\PrivateSale\Model\Splashpage
     */
    protected $splashpage;

    /**
     * Url
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    /**
     * Customer session
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\App\ActionFlag
     */
    protected $_actionFlag;

    /**
     * Conturcor
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\ActionFlag $actionFlag
     * @param \Plumrocket\PrivateSale\Helper\Preview $previewHelper
     * @param \Plumrocket\PrivateSale\Helper\Data   $helper
     * @param \Magento\Customer\Model\Url $customerUrl
     */
    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Plumrocket\PrivateSale\Helper\Preview $previewHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Magento\Framework\UrlInterface $url,
        \Plumrocket\PrivateSale\Helper\Data $helper,
        \Magento\Customer\Model\Url $customerUrl,
        \Plumrocket\PrivateSale\Model\Splashpage $splashpage
    ) {
        $this->splashpage = $splashpage;
        $this->messageManager = $messageManager;
        $this->previewHelper = $previewHelper;
        $this->helper = $helper;
        $this->customerUrl = $customerUrl;
        $this->url = $url;
        $this->customerSession = $customerSession;
        $this->_actionFlag = $actionFlag;
    }

    /**
     * {@inheritdoc}
     * @param  \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $front = $observer->getEvent()->getControllerAction();
        $data = $observer->getEvent()->getData();
        if ($this->customerSession->getPrivatesaleViewPage() && $this->customerSession->getCustomerGroupId() && !$front->getRequest()->isXmlHttpRequest()) {
            $url = $this->customerSession->getPrivatesaleViewPage();
            $this->customerSession->setPrivatesaleViewPage(null);
            $data['controller_action']->getResponse()->setRedirect($url)->sendResponse();
            $data['controller_action']->getResponse()->setDispatched(true);
            $this->_actionFlag->set('', \Magento\Framework\App\ActionInterface::FLAG_NO_DISPATCH, true);
            return;
        }

        if (!$this->helper->moduleEnabled() || $this->previewHelper->isAllowPreview() || $this->customerSession->getCustomerGroupId()) {
            return $observer;
        }


        if ($this->_canRedirect($front)) {
            if ($this->splashpage->isEnabledRedirect()/* && !$this->messageManager->authenticate($front)*/) {

                $module = $front->getRequest()->getRouteName();
                $controller = $front->getRequest()->getControllerName();

                if ($this->splashpage->getData('enabled_page') == Splashpagestatus::STATUS_ENABLED_DEFAULT) {
                    $redirectUrl = $this->customerUrl->getLoginUrl();
                    $this->messageManager->addNotice(__('Please note that you need to be authenticated user to browse this website. Please log in or register to continue.'));
                } else {
                    $redirectUrl = $this->url->getUrl(
                        'prprivatesale/splashpage/login',
                         $this->customerUrl->getLoginUrlParams()
                    );
                }

                if (!$this->customerSession->getPrivatesaleViewPage()) {
                    $this->customerSession->setPrivatesaleViewPage($this->url->getCurrentUrl());
                }

                $data['controller_action']->getResponse()->setRedirect($redirectUrl)->sendResponse();
                $data['controller_action']->getResponse()->setDispatched(true);
                $this->_actionFlag->set('', \Magento\Framework\App\ActionInterface::FLAG_NO_DISPATCH, true);
            }
        }

        return $this;
    }

    /**
     * Can redirect
     * @param  $front
     * @return boolean
     */
    protected function _canRedirect($front)
    {
        $module = $front->getRequest()->getRouteName();
        $controller = $front->getRequest()->getControllerName();
        $action = strtolower($front->getRequest()->getActionName());

        if ($module == 'customer' && $controller == 'account'
            && (!in_array($action, ['index', 'login', 'create', 'forgotpassword']))
        ) {
            return false;
        }

        if ($module == 'customer' && $controller == 'account' && $this->splashpage->getData('enabled_page') != Splashpagestatus::STATUS_ENABLED_DEFAULT) {
            return true;
        }

        return ($module != 'prprivatesales' && $controller != 'splashpage')
            && ($module != 'customer' && $controller != 'account')
            && ($module != 'contacts')
            && ($module != 'cms' || ($module == 'cms' && $controller == 'index'))
            && ($module != 'contact');
    }
}