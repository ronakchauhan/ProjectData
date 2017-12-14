<?php

namespace Ktpl\PincodeZone\Controller\Adminhtml\Zone;

use Magento\Backend\App\Action;
use Ktpl\PincodeZone\Model\Zone;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Ktpl_PincodeZone::manage_zones';

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

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Zone::STATUS_ENABLED;
            }
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            /** @var \Ktpl\PincodeZone\Model\Zone $model */
            $model = $this->_objectManager->create('Ktpl\PincodeZone\Model\Zone');

            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();

                if (isset($data['pincode_zones']) && is_string($data['pincode_zones'])
                ) {
                    $this->setPostedPincodes($model,$data);
                }

                $this->messageManager->addSuccess(__('You saved the zone.'));
                $this->dataPersistor->clear('ktpl_pincode_zone');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the zone.'));
            }

            $this->dataPersistor->set('ktpl_pincode_zone', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    function setPostedPincodes($model, $post)
    {
        $pincodes['pincodes'] = json_decode($post['pincode_zones'], true);

        if (isset($pincodes)) 
        {
            $pincodeIds = $pincodes['pincodes'];

            try {
                $oldPincodes = $model->getStorePincodes($model);
                $newPincodes = (array) $pincodeIds;

                $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
                $connection = $this->_resources->getConnection();

                $table = $this->_resources->getTableName('ktpl_pincode_zone_relation');
                $insert = array_diff_key($newPincodes, $oldPincodes);
                $delete = array_diff_key($oldPincodes, $newPincodes);

                if ($delete) {
                    $deletepro = array_keys($delete);
                    $where = ['pincode_id IN(?)' => $deletepro, 'zone_id=?' => (int)$model->getId()];
                    $connection->delete($table, $where);
                }

                if ($insert) {
                    $data = [];
                    foreach ($insert as $key => $product_data) {
                        $data[] = ['zone_id' => (int)$model->getId(), 'pincode_id' => (int)$key];
                    }
                    $connection->insertMultiple($table, $data);
                }

            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the brand.'));
            }
        }
    }
}
