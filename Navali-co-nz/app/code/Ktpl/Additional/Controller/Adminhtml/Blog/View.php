<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Additional\Controller\Adminhtml\Blog;

use Magento\Backend\App\Action;

class View extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Ktpl_Additional::save';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    protected $_objectManager;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_objectManager = $context->getObjectManager();
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ktpl_Additional::manage_bags')
            ->addBreadcrumb(__('Bag Inquiry'), __('Bag Inquiry'))
            ->addBreadcrumb(__('Manage Bag Inquiries'), __('Manage Bag Inquiries'));
        return $resultPage;
    }

    /**
     * Edit CMS page
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {

        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Ktpl\Additional\Model\Bags');

        // 2. Initial checking
        if ($id) {
            // echo "<pre>"; print_r($model->getData());exit;
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This page no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('additional_blog_listing_data_source', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('View bag Inquiry') : __('View bag Inquiry'),
            $id ? __('View bag Inquiry') : __('View bag Inquiry')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('View Bag Inquiry'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Banner'));

        if (!$this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->isSingleStoreMode()
            &&
            ($switchBlock = $resultPage->getLayout()->getBlock('store_switcher'))
        ) {
            $switchBlock->setDefaultStoreName(__('Default Values'))
                ->setWebsiteIds($model->getWebsiteIds())
                ->setSwitchUrl(
                    $this->getUrl(
                        'additional/*/*',
                        ['_current' => true, 'active_tab' => null, 'tab' => null, 'store' => null]
                    )
                );
        }

        return $resultPage;
    }
}
