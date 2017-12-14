<?php
/**
 * Copyright © 2016 ktpl (http://www.ktpl.co/) All rights reserved.
 */

namespace Ktpl\Productslider\Controller\Adminhtml;

abstract class Slider extends \Magento\Backend\App\Action {

    /**
     * These variables will be used in child controller classes like Index, Grid, Close
     */

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $_resultForwardFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $_resultRedirectFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $_layoutFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $_resultRawFactory;

    /**
     * @var \Ktpl\Productslider\Model\ProductsliderFactory
     */
    protected $_sliderFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Ktpl\Productslider\Model\ProductsliderFactory $productsliderFactory
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Ktpl\Productslider\Model\ProductsliderFactory $productsliderFactory,
        \Magento\Framework\Registry $coreRegistry
    ){
        $this->_resultPageFactory = $resultPageFactory;
        $this->_layoutFactory = $layoutFactory;
        $this->_resultRawFactory = $resultRawFactory;
        $this->_resultForwardFactory = $resultForwardFactory;
        $this->_resultRedirectFactory = $context->getResultRedirectFactory();
        $this->_sliderFactory = $productsliderFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ktpl_Productslider::manage_sliders');
    }

    /**
     * Initialize and return slider object
     *
     * @param int $sliderId
     * @return \Ktpl\Productslider\Model\ProductsliderFactory
     */
    protected function _initSlider($sliderId)
    {
        $model = $this->_sliderFactory->create();

        if ($sliderId) {
            $model->load($sliderId);
        }

        return $model;
    }

}