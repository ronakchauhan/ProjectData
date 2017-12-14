<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Blog\Controller\Adminhtml\Tag\Type;

use Magento\Framework\App\Request\DataPersistorInterface;

class Save extends \Ktpl\Blog\Controller\Adminhtml\Tag\Type
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

        if($data['visibility'] == '1')
        {
            if($data['visible_category'])
            {
                $data['visible_category'] = implode(",", $data['visible_category']);
                $data['hide_category'] = "";
            }
        }
        elseif($data['visibility'] == '2')
        {
            if($data['hide_category'])
            {
                $data['hide_category'] = implode(",", $data['hide_category']);
                $data['visible_category'] = "";
            }
        }
        else
        {
            $data['hide_category'] = "";
            $data['visible_category'] = "";
        }

        if (!$data) {
            $this->_redirect('blog/tag_type/new');
            return;
        }
        try {
            $rowData = $this->_objectManager->create('Ktpl\Blog\Model\Tag\Type');

            if (isset($data['tag_type_id']) && $data['tag_type_id'] == '') {
                
                unset($data['tag_type_id']);
            }

            $rowData->setData($data);

            $rowData->save();

            $tagId = $rowData->getTagId();

            $this->dataPersistor->clear('ktpl_blog_Tags');
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['tag_type_id' => $rowData->getId(), '_current' => true]);
            }

            $this->messageManager->addSuccess(__('Tag Type has been successfully saved.'));
        } catch (Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('*/*/');
    }
}

