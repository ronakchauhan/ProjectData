<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Blog\Controller\Adminhtml\Tag;

use Magento\Framework\App\Request\DataPersistorInterface;

class Save extends \Ktpl\Blog\Controller\Adminhtml\Tag
{ 
    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        DataPersistorInterface $dataPersistor
    ) {

        $this->date = $date;
        $this->dataPersistor = $dataPersistor;

        parent::__construct($context);
    }

    /**
     * Save Banner
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        $data = $this->getRequest()->getPostValue();


        if (!$data) {
            $this->_redirect('blog/tag/new');
            return;
        }
        try {
            $rowData = $this->_objectManager->create('Ktpl\Blog\Model\Tag');

            if (isset($data['tag_id']) && $data['tag_id'] == '') {
                
                unset($data['tag_id']);
            }

            $rowData->setData($data);

            $rowData->save();

            $tagId = $rowData->getTagId();

            $this->dataPersistor->clear('ktpl_blog_Tags');
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['tag_id' => $rowData->getId(), '_current' => true]);
            }

            $this->messageManager->addSuccess(__('Tag has been successfully saved.'));
        } catch (Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('*/*/');
    }
}

