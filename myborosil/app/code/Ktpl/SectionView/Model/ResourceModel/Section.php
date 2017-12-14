<?php

namespace Ktpl\SectionView\Model\ResourceModel;

use Ktpl\SectionView\Model\Section as ModelSection;

class Section extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Image uploader
     *
     * @var \Ktpl\SectionView\SectionImageUploader
     */
    private $imageUploader;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ktpl_section', 'entity_id');
    }

    /**
     * set Stores
     *
     * @param ModelSection $section
     * @return $this
     */
    public function setStores(ModelSection $section)
    {
        $connection = $this->getConnection();
        $connection->delete($this->getTable('ktpl_section_store'), ['entity_id = ?' => $section->getId()]);

        $stores = $section->getStores();

        if (!is_array($stores)) {
            $stores = [];
        }

        foreach ($stores as $storeId) {
            $data = [];
            $data['store_id'] = $storeId;
            $data['entity_id'] = $section->getId();
            $connection->insert($this->getTable('ktpl_section_store'), $data);
        }

        return $this;
    }

    /**
     * get Stores
     *
     * @param ModelSection $section
     * @return int[]
     */
    public function getStores(ModelSection $section)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('ktpl_section_store'),
            'store_id'
        )->where(
            'entity_id = :entity_id'
        );

        if (!($result = $connection->fetchCol($select, ['entity_id' => $section->getId()]))) {
            $result = [];
        }

        return $result;
    }

    /**
     * Process section data before saving
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
     * Process section data before deleting
     *
     * @param AbstractModel $object
     * @return $this
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $condition = ['entity_id = ?' => (int)$object->getId()];
        $this->getConnection()->delete($this->getTable('ktpl_section_store'), $condition);

        return parent::_beforeDelete($object);
    }

    /**
     * Get image uploader
     *
     * @return \Ktpl\SectionView\SectionImageUploader
     *
     * @deprecated
     */
    private function getImageUploader()
    {
        if ($this->imageUploader === null) {
            $this->imageUploader = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Ktpl\SectionView\SectionImageUploader'
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
