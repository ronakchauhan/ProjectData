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

namespace Plumrocket\PrivateSale\Block;


use \Magento\Customer\Model\Url as CustomerUrl;

class Splashpage extends \Magento\Framework\View\Element\Template
{

    /**
     * Splash Page
     * @var \Plumrocket\PrivateSale\Model\Splashpage
     */
	public $splashPage;

    /**
     * Logo
     * @var Magento\Theme\Block\Html\Header\Logo
     */
	protected $logo;

    /**
     * Filter provider
     * @var Magento\Cms\Model\Template\FilterProvider
     */
    protected $filterProvider;

    /**
     * Customer session
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * Json helper
     * @var Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * Constructor
     * @param \Plumrocket\PrivateSale\Model\Splashpage         $splashPage
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session                  $customerSession
     * @param \Magento\Framework\Json\Helper\Data              $jsonHelper,
     * @param \Magento\Theme\Block\Html\Header\Logo            $logo
     * @param \Magento\Cms\Model\Template\FilterProvider       $filterProvider
     * @param array                                            $data
     */
	public function __construct(
		\Plumrocket\PrivateSale\Model\Splashpage $splashPage,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Theme\Block\Html\Header\Logo $logo,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        $data = []
	) {
		$this->splashPage = $splashPage;
        $this->filterProvider = $filterProvider;
		$this->logo = $logo;
        $this->jsonHelper = $jsonHelper;
        $this->customerSession = $customerSession;
		parent::__construct($context, $data);
	}

    /**
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set($this->splashPage->getMetaTitle());
        $this->pageConfig->setDescription($this->splashPage->getMetaDescription());
        $this->pageConfig->setKeywords($this->splashPage->getMetaKeywords());

    }

	/**
	 * Retrieve base url
	 * @return string
	 */
	public function getBaseUrl()
	{
		return $this->_storeManager->getStore()->getBaseUrl();
	}

    /**
     * Retrieve media ur
     * @return string
     */
    public function getPubMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

	/**
	 * Is enabled lauching soon mode
	 * @return boolean
	 */
	public function isEnabledLaunchingSoon()
    {
        return $this->splashPage->getEnabledLaunchingSoon();
    }

    /**
     * Get logo image URL
     *
     * @return string
     */
    public function getLogoSrc()
    {
        return $this->logo->getLogoSrc();
    }

    /**
     * Get logo text
     *
     * @return string
     */
    public function getLogoAlt()
    {
        return $this->logo->getLogoAlt();
    }

    // Logins
    public function isUserLogin()
    {
        return (!$this->splashPage->getEnabledPage())
            || (!$this->splashPage->getEnabledLaunchingSoon());
    }

    // Registration
    public function isUserRegistration()
    {
        return (!$this->splashPage->getEnabledPage())
            || ($this->splashPage->getEnabledRegistration());
    }

    /**
     * Rertrieve images json
     * @return string
     */
    public function getImagesJson()
    {
        $images = $this->splashPage->getImages();
        return $this->jsonHelper->jsonEncode($images);
    }

    /**
     * Is splash page enabled
     * @return boolean
     */
    public function isEnabledPage()
    {
        return $this->splashPage->getEnabledPage();
    }

    /**
     * Retrieve post action url
     * @return string
     */
    public function getPostActionUrl()
    {
        return $this->getUrl('customer/ajax/login');
    }

    /**
     * Filter content
     * @param  string $content
     * @return string
     */
    public function filter($content = '')
    {
        return $this->filterProvider->getBlockFilter()->filter($content);
    }

    /**
     * Retrieve base url to media files
     * @return string
     */
    public function getSplashPageMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . DIRECTORY_SEPARATOR . 'splashpage';
    }
}
