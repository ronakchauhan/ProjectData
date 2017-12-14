<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Testimonial\Controller\Adminhtml\Index;

use Magento\Framework\App\Request\DataPersistorInterface;

class Save extends \Magento\Backend\App\Action
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

        $storeId = $this->getRequest()->getParam('store', 0);
        $store = $this->getStoreManager()->getStore($storeId);
        $this->getStoreManager()->setCurrentStore($store->getCode());
        
        $data = $this->getRequest()->getPostValue();


        if (!$data) {
            $this->_redirect('testimonial/index/new');
            return;
        }
        try {
            $rowData = $this->_objectManager->create('Ktpl\Testimonial\Model\Testimonial');

            $createdate = $this->date->gmtDate();
            
            $data['updated_at'] = $createdate;

            if (isset($data['entity_id']) && $data['entity_id'] == '') {
                
                unset($data['entity_id']);

                $data['created_at'] = $createdate;
            }

            $rowData->setData($data);

            $rowData->setStores($this->getRequest()->getParam('store_id', []));

            $rowData->save();

            $entityId = $rowData->getEntityId();

            if (isset($data['entity_id']) && $data['entity_id'] != "") 
            {
                /**
                 * @todo call observer of current module \Ktpl\Testimonial\Observer\TestimonialSaveAfter
                 */
                $this->_eventManager->dispatch(
                    'testimonial_save_after',
                    ['testimonial' => $data, 'request' => $this->getRequest(), 'response' => $this->getResponse()]
                );
            
            }

            $this->dataPersistor->clear('ktpl_testimonials');
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $rowData->getId(), '_current' => true]);
            }

            $this->messageManager->addSuccess(__('Testimonial has been successfully saved.'));
        } catch (Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('*/*/');
    }

    /**
     * @return StoreManagerInterface
     * @deprecated
     */
    private function getStoreManager()
    {
        if (null === $this->storeManager) {
            $this->storeManager = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Store\Model\StoreManagerInterface');
        }
        return $this->storeManager;
    }

}

