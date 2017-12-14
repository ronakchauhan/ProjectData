<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Blog\Model\Tag;

use Ktpl\Blog\Model\ResourceModel\Tag\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Ktpl\Blog\Model\ResourceModel\Tag\Collection
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
     * @param CollectionFactory $tagCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $tagCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $tagCollectionFactory->create();
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
        
        /** @var $tag \Ktpl\Testimonial\Model\Testimonial */
        foreach ($items as $tag) {
            /* get item Data */
            $itemData = $tag->getData();
            
            /* Load/Set Edit Data*/
            $this->loadedData[$tag->getId()] = $itemData;
        }

        $data = $this->dataPersistor->get('ktpl_blog_tag');

        if (!empty($data)) {
            $tag = $this->collection->getNewEmptyItem();
            $tag->setData($data);
            $this->loadedData[$tag->getId()] = $tag->getData();

            $this->dataPersistor->clear('ktpl_blog_tag');
        }
        return $this->loadedData;
    }
}
