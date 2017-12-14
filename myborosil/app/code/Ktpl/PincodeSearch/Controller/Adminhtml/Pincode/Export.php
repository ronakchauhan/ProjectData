<?php

namespace Ktpl\PincodeSearch\Controller\Adminhtml\Pincode;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class Export extends \Magento\Backend\App\Action
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

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(        
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
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
        $fileName = 'pincodes.csv';

        $gridBlock = $this->_view->getLayout()->createBlock(
            'Ktpl\PincodeSearch\Block\Adminhtml\Pincode\Grid'
        );

        $content = $gridBlock->getCsvFile();
        
        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
