<?php

namespace Ktpl\BannerSlider\Model\ResourceModel;

use Ktpl\BannerSlider\Model\Banner as ModelBanner;

class Banner extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Image uploader
     *
     * @var \Ktpl\BannerSlider\BannerImageUploader
     */
    private $imageUploader;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ktpl_banner', 'entity_id');
    }

    /**
     * set Stores
     *
     * @param ModelBanner $banner
     * @return $this
     */
    public function setStores(ModelBanner $banner)
    {
        $connection = $this->getConnection();
        $connection->delete($this->getTable('ktpl_banner_store'), ['entity_id = ?' => $banner->getId()]);

        $stores = $banner->getStores();

        if (!is_array($stores)) {
            $stores = [];
        }

        foreach ($stores as $storeId) {
            $data = [];
            $data['store_id'] = $storeId;
            $data['entity_id'] = $banner->getId();
            $connection->insert($this->getTable('ktpl_banner_store'), $data);
        }

        return $this;
    }

    /**
     * get Stores
     *
     * @param ModelBanner $banner
     * @return int[]
     */
    public function getStores(ModelBanner $banner)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('ktpl_banner_store'),
            'store_id'
        )->where(
            'entity_id = :entity_id'
        );

        if (!($result = $connection->fetchCol($select, ['entity_id' => $banner->getId()]))) {
            $result = [];
        }

        return $result;
    }

    /**
     * Process banner data before saving
     *
     * @param AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getSaveStoresFlag()) {
            $this->setStores($object);
        }

        $this->moveImage($object);

        return parent::_afterSave($object);
    }

    /**
     * Process banner data before deleting
     *
     * @param AbstractModel $object
     * @return $this
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $condition = ['entity_id = ?' => (int)$object->getId()];
        $this->getConnection()->delete($this->getTable('ktpl_banner_store'), $condition);

        return parent::_beforeDelete($object);
    }

    /**
     * Get image uploader
     *
     * @return \Ktpl\BannerSlider\BannerImageUploader
     *
     * @deprecated
     */
    private function getImageUploader()
    {
        if ($this->imageUploader === null) {
            $this->imageUploader = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Ktpl\BannerSlider\BannerImageUploader'
            );
        }
        return $this->imageUploader;
    }

    /**
     * Save uploaded file
     *
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    private function moveImage($object)
    {
        $image = $object->getData('image', null);

        if ($image !== null) {
            try {
                $this->getImageUploader()->moveFileFromTmp($image);
            } catch (\Exception $e) {
                // $this->_logger->critical($e);
            }
        }
        return $this;
    }
}
