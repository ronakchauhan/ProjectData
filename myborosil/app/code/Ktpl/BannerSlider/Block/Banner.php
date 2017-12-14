<?php

namespace Ktpl\BannerSlider\Block;

class Banner extends \Magento\Framework\View\Element\Template
{
	protected $_itemCollectionFactory;

	/**
     * Directory List
     *
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;

    public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
        \Ktpl\BannerSlider\Model\ResourceModel\Banner\CollectionFactory $itemCollectionFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        array $data = []
    ) {
        $this->_itemCollectionFactory = $itemCollectionFactory;
        $this->_directoryList         = $directoryList;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve image URL
     *
     * @return string
     */
    public function getImageUrl($image, $path)
    {
        $url = false;
        if ($image) {
            if (is_string($image)) {
                $url = $this->_storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . $path . '/' . $image;
            }
        }
        return $url;
    }

    /**
     * get image size
     *
     * @return array
     */
    public function getImageSize($image, $path)
    {
        $filePath = $this->_directoryList->getPath('pub');
        $filePath = $filePath . '/media/' . $path . "/" . $image;

        if(file_exists($filePath))
            return getimagesize($filePath);

        return [];
    }
    
    public function getItemCollection(){       

        $collection = $this->_itemCollectionFactory->create();
        $collection->addFieldToFilter('is_active',['eq'=>1])->setOrder('sortorder', 'ASC');


        return $collection;
	}
}
