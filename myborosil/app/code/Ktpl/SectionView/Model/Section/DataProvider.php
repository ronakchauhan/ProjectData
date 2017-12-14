<?php

namespace Ktpl\SectionView\Model\Section;

use Ktpl\SectionView\Model\ResourceModel\Section\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

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
     * @param CollectionFactory $sectionCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $sectionCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $sectionCollectionFactory->create();
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
        /** @var $section \Ktpl\SectionView\Model\Section */
        foreach ($items as $section) {
            /* get image url */
            $sectionData = $section->getData();
            if (isset($sectionData['image'])) {
                unset($sectionData['image']);
                $sectionData['image'][0]['name'] = $section->getData('image');
                $sectionData['image'][0]['url'] = $this->getImageUrl($section->getData('image'));
            }
            $this->loadedData[$section->getId()] = $sectionData;
        }

        $data = $this->dataPersistor->get('cms_sections');
        if (!empty($data)) {
            $section = $this->collection->getNewEmptyItem();
            $section->setData($data);
            $this->loadedData[$section->getId()] = $section->getData();
            $this->dataPersistor->clear('cms_sections');
        }

        return $this->loadedData;
    }

    /**
     * Retrieve image URL
     *
     * @return string
     */
    public function getImageUrl($image)
    {
        $url = false;
        if ($image) {
            if (is_string($image)) {
                $url = $this->_storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . 'sections/image/' . $image;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }
}
