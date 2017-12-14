<?php

namespace Ktpl\PincodeZone\Controller\Adminhtml\Zone;

class Index extends \Magento\Backend\App\Action
{
	const ADMIN_RESOURCE = 'Ktpl_PincodeZone::manage_zones';

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
        $resultPage->setActiveMenu('Ktpl_PincodeZone::manage_zones');
        $resultPage->addBreadcrumb(__('Pincode Zone'), __('Pincode Zone'));
        $resultPage->addBreadcrumb(__('Manage Zones'), __('Manage Zones'));
        $resultPage->getConfig()->getTitle()->prepend(__('Zones'));

        $dataPersistor = $this->_objectManager->get('Magento\Framework\App\Request\DataPersistorInterface');
        $dataPersistor->clear('ktpl_pincode_zone');

        return $resultPage;
	}
}