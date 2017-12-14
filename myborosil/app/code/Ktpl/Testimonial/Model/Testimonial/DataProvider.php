<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Testimonial\Model\Testimonial;

use Ktpl\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Ktpl\Testimonial\Model\ResourceModel\Testimonial\Collection
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
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
    
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $testimonialCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $testimonialCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $testimonialCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
        $this->_objectManager = $objectmanager;
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $storeManager = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface'); 
        $currentStore = $storeManager->getStore();
        
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        
        /** @var $testimonial \Ktpl\Testimonial\Model\Testimonial */
        foreach ($items as $testimonial) {
            /* get item Data */
            $itemData = $testimonial->getData();
            
            /* Load/Set Edit Data*/
            $this->loadedData[$testimonial->getId()] = $itemData;
        }

        $data = $this->dataPersistor->get('ktpl_testimonials');

        if (!empty($data)) {
            $testimonial = $this->collection->getNewEmptyItem();
            $testimonial->setData($data);
            $this->loadedData[$testimonial->getId()] = $testimonial->getData();

            $this->dataPersistor->clear('ktpl_testimonials');
        }
        return $this->loadedData;
    }
}
