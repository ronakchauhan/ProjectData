<?php

namespace Ktpl\PincodeSearch\Controller\Adminhtml\Pincode;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class Import extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Ktpl_PincodeSearch::manage_pincodes';

    protected $resultPageFactory;

    protected $resultForwardFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(        
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ktpl_PincodeSearch::manage_pincodes')
            ->addBreadcrumb(__('Import Pincodes'), __('Import Pincodes'));
        return $resultPage;
    }

    public function execute()
    {
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            __('Import Pincodes'),
            __('Import Pincodes')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Import Pincodes'));
        $resultPage->getConfig()->getTitle()
            ->prepend(__('Import Pincodes'));

        return $resultPage;
    }
}
