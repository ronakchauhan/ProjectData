<?php

namespace Ktpl\PincodeSearch\Controller\Adminhtml\Pincode;

class Index extends \Magento\Backend\App\Action
{
	const ADMIN_RESOURCE = 'Ktpl_PincodeSearch::manage_pincodes';

	protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

	public function execute()
	{
		$resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ktpl_PincodeSearch::manage_pincodes');
        $resultPage->addBreadcrumb(__('Pincode Search'), __('Pincode Search'));
        $resultPage->addBreadcrumb(__('Manage Pincodes'), __('Manage Pincodes'));
        $resultPage->getConfig()->getTitle()->prepend(__('Pincodes'));

        $dataPersistor = $this->_objectManager->get('Magento\Framework\App\Request\DataPersistorInterface');
        $dataPersistor->clear('ktpl_homefeatured_slider');

        return $resultPage;
	}
}