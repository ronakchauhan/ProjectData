<?php

namespace Ktpl\Blog\Ui\DataProvider\Tag\Form;

use Ktpl\Blog\Model\ResourceModel\Tag\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class TagDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
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
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $tagCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
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
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var $tag \Ktpl\Blog\Model\Tag */
        foreach ($items as $tag) {
            $this->loadedData[$tag->getId()] = $tag->getData();
        }

        $data = $this->dataPersistor->get('current_model');
        if (!empty($data)) {
            $tag = $this->collection->getNewEmptyItem();
            $tag->setData($data);
            $this->loadedData[$tag->getId()] = $tag->getData();
            $this->dataPersistor->clear('current_model');
        }

        return $this->loadedData;
    }
}
