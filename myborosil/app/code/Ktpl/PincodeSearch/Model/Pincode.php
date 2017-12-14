<?php

namespace Ktpl\PincodeSearch\Model;

class Pincode extends \Magento\Framework\Model\AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    protected function _construct()
    {
        $this->_init('Ktpl\PincodeSearch\Model\ResourceModel\Pincode');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    public function getCODStatuses()
    {
        return [self::STATUS_ENABLED => __('Yes'), self::STATUS_DISABLED => __('No')];
    }

    /**
     * Retrieve product id by sku
     *
     * @param   string $sku
     * @return  integer
     */
    public function loadByPincode($pincode)
    {
        $id = $this->_getResource()->getIdByPincode($pincode);

        if(isset($id))
            $this->load($id);

        return $this;
    }
}
