<?php

namespace Ktpl\BannerSlider\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Ktpl\BannerSlider\Model\Banner;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Ktpl_BannerSlider::banners';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Banner::STATUS_ENABLED;
            }
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            if(isset($data['image']['0'])) {
                $data['image'] = $data['image']['0']['name'];
            }
            else {
                $data['image'] = null;
            }

            /** @var \Ktpl\BannerSlider\Model\Banner $model */
            $model = $this->_objectManager->create('Ktpl\BannerSlider\Model\Banner');

            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);
            $model->setStores($this->getRequest()->getParam('store_id', []));

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the banner.'));
                $this->dataPersistor->clear('ktpl_banners');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                echo"<pre/>"; print_r($e->getMessage());exit;
                $this->messageManager->addException($e, __('Something went wrong while saving the banner.'));
            }

            $this->dataPersistor->set('ktpl_banners', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
