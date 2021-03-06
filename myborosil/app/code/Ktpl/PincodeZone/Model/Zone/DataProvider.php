<?php

namespace Ktpl\PincodeZone\Model\Zone;

use Ktpl\PincodeZone\Model\ResourceModel\Zone\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Ktpl\PincodeZone\Model\ResourceModel\Zone\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $zoneCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $zoneCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $zoneCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->_storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var $pincode \Ktpl\PincideSearch\Model\Pincode */
        foreach ($items as $pincode) {
            /* get image url */
            $this->loadedData[$pincode->getId()] = $pincode->getData();
        }

        $data = $this->dataPersistor->get('ktpl_pincode_zone');
        if (!empty($data)) {
            $pincode = $this->collection->getNewEmptyItem();
            $pincode->setData($data);
            $this->loadedData[$pincode->getId()] = $pincode->getData();
            $this->dataPersistor->clear('ktpl_pincode_zone');
        }

        return $this->loadedData;
    }
}
