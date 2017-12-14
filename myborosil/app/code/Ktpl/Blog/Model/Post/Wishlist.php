<?php

namespace Ktpl\Blog\Model\Post;

class Wishlist extends \Magento\Framework\Model\AbstractModel
{
    /**#@+
     * Banner's statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * @var int[]
     */
    protected $_stores = [];

    /**
     * @var boolean
     */
    protected $_saveStoresFlag = false;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ktpl\Blog\Model\ResourceModel\Post\Wishlist');
    }

    /**
     * Prepare statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * Set store flag
     *
     * @param boolean $value
     * @return $this
     */
    public function setSaveStoresFlag($value)
    {
        $this->_saveStoresFlag = (bool)$value;
        return $this;
    }

    /**
     * Receive store flag
     *
     * @return boolean
     */
    public function getSaveStoresFlag()
    {
        return $this->_saveStoresFlag;
    }

    /**
     * Set store ids
     *
     * @return $this
     */
    public function setStores(array $storesIds)
    {
        $this->setSaveStoresFlag(true);
        $this->_stores = $storesIds;
        return $this;
    }

    /**
     * Receive store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        if (!$this->_stores) {
            $this->_stores = $this->_getResource()->getStores($this);
        }

        return $this->_stores;
    }
}
