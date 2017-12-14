<?php
/**
 * Copyright © 2016 AionNext Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Testimonial\Model\ResourceModel;

use Ktpl\Testimonial\Model\Testimonial as ModelSection;
/**
 * Testimonial resource model
 */
class Testimonial extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ktpl_testimonials', 'entity_id');
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
        $connection->delete($this->getTable('ktpl_testimonials_store'), ['entity_id = ?' => $section->getId()]);

        $stores = $section->getStores();

        if (!is_array($stores)) {
            $stores = [];
        }

        foreach ($stores as $storeId) {
            $data = [];
            $data['store_id'] = $storeId;
            $data['entity_id'] = $section->getId();
            $connection->insert($this->getTable('ktpl_testimonials_store'), $data);
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
            $this->getTable('ktpl_testimonials_store'),
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
        $this->getConnection()->delete($this->getTable('ktpl_testimonials_store'), $condition);

        return parent::_beforeDelete($object);
    }      
}