<?php

namespace Ktpl\CategoryView\Block\Banner;

class Renderer extends \Magento\Framework\View\Element\AbstractBlock
{
	/**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

     /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

	/**
     * Retrieve section content
     *
     * @param string $identifier
     * @return string
     */
    protected function _getSectionHtml($identifier)
    {
        return $this->getLayout()->createBlock('\Ktpl\SectionView\Block\View')
            ->setIdentifier($identifier)->toHtml();
        
        return '';
    }

    /**
     * Prepare HTML content
     *
     * @return string
     */
    protected function _toHtml()
    {
    	$bannerBlock = $this->getCurrentCategory()->getBannerBlock();
    	if(isset($bannerBlock))
    		return $this->_getSectionHtml($bannerBlock);
    	
        return "";
    }

    public function getCurrentCategory()
    {
        return $this->_coreRegistry->registry('current_category');
    }
}
