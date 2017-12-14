<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Additional\Model\Bags;

use Ktpl\Additional\Model\ResourceModel\Bags\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\Collection
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

    protected $_filesystem;

    protected $_objectManager;
    
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $pageCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $pageCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $pageCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->_filesystem = $filesystem;
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
        
        /** @var $page \Dedicated\Sliderui\Model\Sliderui */
        foreach ($items as $page) {
            // $this->loadedData[$page->getId()] = $page->getData();

            /* get image url */
            $dedicatedData = $page->getData();
            if (isset($dedicatedData['bag_image'])) {
                unset($dedicatedData['bag_image']);
                $dedicatedData['bag_image'][0]['name'] = $page->getData('bag_image');
                $path = $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                $dedicatedData['bag_image'][0]['url'] = $path . "bagsimages/" . $page->getData('bag_image');
            }
            $this->loadedData[$page->getId()] = $dedicatedData;
            /* get image url */

        }

        $data = $this->dataPersistor->get('bag_query_request');

        
        
        

        if (!empty($data)) {
            $page = $this->collection->getNewEmptyItem();
            $page->setData($data);
            $this->loadedData[$page->getId()] = $page->getData();

            

            $this->dataPersistor->clear('bag_query_request');
        }
        return $this->loadedData;
    }
}
