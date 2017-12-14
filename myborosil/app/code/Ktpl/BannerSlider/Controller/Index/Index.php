<?php

namespace Ktpl\BannerSlider\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{

	/**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Loads page content
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $isEnabled =  $this->_scopeConfig->getValue('banners/general/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        if ($isEnabled) 
        {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('Banner Slider'));
            
            return $resultPage;
        }
        else
        {
            echo __("Please Enable Module!");
            return false;
        }
        
    	
    }

}