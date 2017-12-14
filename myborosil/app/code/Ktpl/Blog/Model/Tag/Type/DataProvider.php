<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Blog\Model\Tag\Type;

use Ktpl\Blog\Model\ResourceModel\Tag\Type\CollectionFactory;
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
        CollectionFactory $tagTypeCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $tagTypeCollectionFactory->create();
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
        
        /** @var $tagType \Ktpl\Testimonial\Model\Testimonial */
        foreach ($items as $tagType) {
            /* get item Data */
            $itemData = $tagType->getData();
            
            if($itemData['visible_category'])
            {
                $itemData['visible_category'] = explode(",", $itemData['visible_category']);
            }

            if($itemData['hide_category'])
            {
                $itemData['hide_category'] = explode(",",$itemData['hide_category']);
            }

            //echo"<pre/>"; print_r($itemData);exit;

            /* Load/Set Edit Data*/
            $this->loadedData[$tagType->getId()] = $itemData;
        }

        $data = $this->dataPersistor->get('ktpl_blog_tag_type');

        if (!empty($data)) {
            $tagType = $this->collection->getNewEmptyItem();
            $tagType->setData($data);
            $this->loadedData[$tagType->getId()] = $tagType->getData();

            $this->dataPersistor->clear('ktpl_blog_tag_type');
        }
        return $this->loadedData;
    }
}
