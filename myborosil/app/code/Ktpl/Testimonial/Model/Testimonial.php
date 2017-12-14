<?php

namespace Ktpl\Testimonial\Model;

use Ktpl\Testimonial\Api\Data\TestimonialInterface;
use Ktpl\Testimonial\Model\ResourceModel\Testimonial as ResourceTestimonial;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Ktpl Testimonial model
 *
 * @method ResourceTestimonial _getResource()
 * @method ResourceTestimonial getResource()
 * @method Testimonial setStoreId(array $storeId)
 * @method array getStoreId()
 */
class Testimonial extends \Magento\Framework\Model\AbstractModel implements TestimonialInterface
{ 
    /**
     * Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * Aion Test cache tag
     */
    const CACHE_TAG = 'testimonial';

    /**
     * @var string
     */
    protected $_cacheTag = 'testimonial';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'testimonial';

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
        $this->_init('Ktpl\Testimonial\Model\ResourceModel\Testimonial');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getEntityId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle()
    {
        return parent::getData(self::TITLE);
    }

    /**
     * Get Customer Name
     *
     * @return string
     */
    public function getName()
    {
        return parent::getData(self::NAME);
    }

    /**
     * Get valid to date
     *
     * @return string|null
     */
    public function getDate()
    {
        return parent::getData(self::DATE);
    }

    /**
     * Get Description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return parent::getData(self::DESCRIPTION);  
    }

    /**
     * Get Created Date
     *
     * @return string|null
     */
    public function getCreatedAt()
    {
        return parent::getData(self::CREATED_AT);  
    }

    /**
     * Get Update Date
     *
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return parent::getData(self::UPDATED_AT);  
    }

    /**
     * Get Status
     *
     * @return string|null
     */
    public function getStatus()
    {
        return parent::getData(self::STATUS);  
    }

    /**
     * Set ID
     *
     * @param int $entityId
     * @return TestimonialInterface
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Set Title
     *
     * @param string $title
     * @return TestimonialInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set Name
     * @param string $name
     * @return TestimonialInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set Date
     *
     * @param string $date
     * @return TestimonialInterface
     */
    public function setDate($date)
    {
        return $this->setData(self::DATE, $date);
    }

    /**
     * Set title
     *
     * @param string $description
     * @return TestimonialInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::TITLE, $description);
    }

    /**
     * Set Created Date
     *
     * @param string $createAt
     * @return TestimonialInterface
     */
    public function setCreatedAt($createAt)
    {
        return $this->setData(self::CREATED_AT, $createAt);
    }

    /**
     * Set Created Date
     *
     * @param string $createAt
     * @return TestimonialInterface
     */
    public function setUpdatedAt($createAt)
    {
        return $this->setData(self::UPDATED_AT, $createAt);
    }

    /**
     * Set Status
     *
     * @param string $status
     * @return TestimonialInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);  
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

    /**
     * Prepare item's statuses
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}