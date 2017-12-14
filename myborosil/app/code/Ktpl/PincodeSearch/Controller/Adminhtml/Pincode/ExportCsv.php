<?php

namespace Ktpl\PincodeSearch\Controller\Adminhtml\Pincode;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class ExportCsv extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Ktpl_PincodeSearch::manage_pincodes';

    /**
     * @var \Magento\Backend\Model\View\Result\Forward
     */
    protected $_fileFactory;

    protected $_storeManager;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(        
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->_storeManager = $storeManager;
        $this->_fileFactory = $fileFactory;
        parent::__construct($context);
    }

    /**
     * Forward to edit
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        $fileName = 'pincode.csv';
        $gridBlock = $this->_view->getLayout()->createBlock(
            'Ktpl\PincodeSearch\Block\Adminhtml\Pincode\Grid'
        );
        $website = $this->_storeManager->getWebsite($this->getRequest()->getParam('website'));
        $gridBlock->setWebsiteId($website->getId());
        $content = $gridBlock->getCsvFile();
        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
