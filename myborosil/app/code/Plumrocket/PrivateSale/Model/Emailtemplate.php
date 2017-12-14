<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket Private Sales and Flash Sales v4.x.x
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


namespace Plumrocket\PrivateSale\Model;

class Emailtemplate extends \Magento\Framework\Model\AbstractModel
{
    protected $categoriesCollection;

    /**
     * Category model
     * @var \Magento\Catalog\Model\Category
     */
    protected $categoryFactory;

    /**
     * Data helper
     * @var Plumrocket\PrivateSale\Helper\Data
     */
    protected $dataHelper;

    /**
     * Store manager
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Constructor
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Catalog\Model\CategoryFactory                       $categoryFactory
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     */
    public function __construct(
        \Plumrocket\PrivateSale\Helper\Data $dataHelper,
        \Plumrocket\PrivateSale\Model\Event $event,
        \Magento\Framework\Model\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->dataHelper = $dataHelper;
        $this->categoryFactory = $categoryFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context, $registry, $resource, $resourceCollection);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Plumrocket\PrivateSale\Model\ResourceModel\Emailtemplate');
    }

    /**
     * Retrieve categories collection
     * @return Magento\Catalog\Model\Catagory
     */
    public function getCategoriesCollection()
    {
        if (is_null($this->categoriesCollection)){
            $categoryIds = $this->getCategoriesIds();
            if (is_string($categoryIds)) {
                $categoryIds = array_map('trim', explode(',', $categoryIds));
            }

            $this->categoriesCollection = $this->categoryFactory->create()->getCollection()
                ->setStoreId($this->getStoreId())
                ->addFieldToFilter('entity_id', ['in' => $categoryIds])
                ->addAttributeToSelect('*');
        }
        return $this->categoriesCollection;
    }

    /**
     * Retrieve image URL
     *
     * @return string
     */
    public function getPrivatesaleEmailImageUrl($category)
    {
        $url = false;
        $image = $category->getPrivatesaleEmailImage();

        if (!$image) {
            $image = $category->getPrivatesaleEventImage();
        }

        if ($image) {

            if (is_string($image)) {
                $url = $this->storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . 'catalog/category/' . $image;
            }
        } else {
            return $category->getImageUrl();
        }

        return $url;
    }

    /**
     * Load categories by criteria
     * @param  string $date
     * @param  int $storeId
     * @return array
     */
    public function loadCategoriesByCriteria($date = null, $storeId = null)
    {
        if (is_null($storeId)){
            $storeId = $this->getStoreId();
        }

        if ($date === null ) {
            $date = date("Y-m-d H:i:s");
        } else {
            $date = date("Y-m-d H:i:s", strtotime($date));
        }

        $_categories = $this->categoryFactory->create()->getCollection()
            ->setStoreId($storeId)
            ->addAttributeToSelect('name')
            ->addAttributeToFilter(
                'privatesale_date_start',
                ['lt' => $date]
            );

        $this->dataHelper->addEndDateFilter($_categories, $date);

        return $_categories;
    }

    public function categoriesToOptions($categories)
    {
        $_result = [];
        foreach($categories as $category){
            $_result[] = [
                'value' => $category->getId(),
                'label' => $category->getName(),
            ];
        }
        return $_result;
    }
}
