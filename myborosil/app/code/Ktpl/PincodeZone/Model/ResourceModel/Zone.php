<?php

namespace Ktpl\PincodeZone\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Exception\LocalizedException;

class Zone extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('ktpl_pincode_zone', 'entity_id');
    }
}
