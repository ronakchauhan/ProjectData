<?php

namespace Ktpl\Blog\Helper;

class Layout extends \Magento\Framework\App\Helper\AbstractHelper
{
	/**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $_blockFactory;

    /**
     * @var \Zend_Filter_Interface
     */
    protected $templateProcessor;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     * @param \Zend_Filter_Interface $templateProcessor
     */
	public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Zend_Filter_Interface $templateProcessor
    ) {
        $this->_storeManager = $storeManager;
        $this->_coreRegistry = $coreRegistry;
        $this->_blockFactory = $blockFactory;
        $this->templateProcessor = $templateProcessor;
        parent::__construct($context);
    }

    /**
     * get listing block based on layout
     *
     * @return array
     */
    public function getListingBlockItems()
    {
    	$loadedData = [];

        $blockLayout = $this->_getCurrentBlogCategory()->getBlockLayout();

        if(isset($blockLayout) && $blockLayout != "")
        {
            $blockLayout = json_decode($blockLayout, true);

            $sortOrders = array_map(function($element) { return $element['sort_order']; }, $blockLayout);

            foreach($blockLayout as $block)
            {
                $sortOrder = 0;
                if(isset($block['sort_order']) && $block['sort_order'] != "" && $block['sort_order'] != "0")
                    $sortOrder = intval($block['sort_order']);

                if($sortOrder == 0 || array_key_exists($sortOrder, $loadedData))
                    $sortOrder = $this->getSortOrder($loadedData, min($sortOrders));

                $loadedData[$sortOrder] = [
                    'type' => $block['select_type'],
                    'block' => $block['select_block']
                ];
            }
        }

    	return $loadedData;
    }

    /**
     * Retrieve category instance
     *
     * @return \Ktpl\Blog\Model\Category
     */
    public function _getCurrentBlogCategory()
    {
        return $this->_coreRegistry->registry('current_blog_category');
    }
    /**
     * get unique key
     * 
     * @param array $loadedData
     * @param int $i
     * @return int
     */
    public function getSortOrder($loadedData, $i)
    {
        if($i == 0) $i = 1;

        while(array_key_exists($i, $loadedData))
            $i++;
        return $i;
    }

    /**
     * get the actual filter data
     *
     * @param HTML string
     * @return HTML output
     */
    public function filterOutputHtml($blockArray, $layout)
    {
        if($blockArray['type'] == \Ktpl\CategoryView\Block\Adminhtml\Options::TYPE_BLOCK)
            return $this->_getBlockHtml($blockArray['block'], $layout);

        return $this->_getSectionHtml($blockArray['block'], $layout);
    }

    /**
     * Retrieve block content
     *
     * @param string $identifier
     * @return string
     */
    protected function _getBlockHtml($identifier, $layout)
    {
        $blockObject = $this->_blockFactory->create();
        $blockObject->load($identifier);
        if($blockObject->getId())
            return $this->templateProcessor->filter($blockObject->getContent());

        return '';
    }

    /**
     * Retrieve section content
     *
     * @param string $identifier
     * @return string
     */
    protected function _getSectionHtml($identifier, $layout)
    {
        return $layout->createBlock('\Ktpl\SectionView\Block\View')
            ->setIdentifier($identifier)->toHtml();
        
        return '';
    }
}
