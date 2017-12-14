<?php

namespace Ktpl\PincodeSearch\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;

class Pincode extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('ktpl_pincode_search', 'entity_id');
    }

	protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
    	if (!$this->getIsUniquePincode($object)) {
            throw new LocalizedException(
                __('A pincode already exists please enter unique pincode.')
            );
        }
        return $this;
    }

    protected function getIsUniquePincode($obj)
    {
    	$select = $this->getConnection()->select()
	        ->from(['cb' => $this->getMainTable()],['pincode'])
	        ->where('cb.pincode = ?', $obj->getData('pincode'))
	        ->where('cb.entity_id != ?', $obj->getData('entity_id'));
            
    	if ($this->getConnection()->fetchRow($select)) {
            return false;
        }

        return true;
    }

    /**
     * Get product identifier by sku
     *
     * @param string $sku
     * @return int|false
     */
    public function getIdByPincode($pincode)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from($this->getMainTable(), 'entity_id')->where('pincode = :pincode');

        $bind = [':pincode' => (string)$pincode];

        return $connection->fetchOne($select, $bind);
    }
}
