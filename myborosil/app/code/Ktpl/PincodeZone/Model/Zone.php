<?php

namespace Ktpl\PincodeZone\Model;

use Magento\Framework\Model\AbstractModel;

class Zone extends AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    protected function _construct()
    {
        $this->_init('Ktpl\PincodeZone\Model\ResourceModel\Zone');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    public function getPincodes(\Ktpl\PincodeZone\Model\Zone $object)
    {
        $tbl = $this->getResource()->getTable('ktpl_pincode_zone_relation');
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['pincode_id']
        )
        ->where(
            'zone_id = ?',
            (int)$object->getId()
        );
        return $this->getResource()->getConnection()->fetchCol($select);
    }

    public function getStorePincodes($slider)
    {
        $tbl = $this->getResource()->getTable('ktpl_pincode_zone_relation');
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['pincode_id']
        )
        ->where(
            'zone_id = ?',
            (int)$slider->getId()
        );
        return array_flip($this->getResource()->getConnection()->fetchCol($select));
    }
}
