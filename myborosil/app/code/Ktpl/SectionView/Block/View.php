<?php

namespace Ktpl\SectionView\Block;

class View extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'Ktpl_SectionView::view.phtml';

    /**
     * @var \Ktpl\SectionView\Model\SectionFactory
     */
    protected $_sectionFactory;

    /**
     * Directory List
     *
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;

    /**
     * Is Scrollable Section
     *
     * @var boolean
     */
    protected $_scrollable = false;

    /**
     *  Get Reuqest
     * @var [type]
     */
    protected $_request;

     /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ktpl\SectionView\Model\SectionFactory $sectionFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        array $data = []
    ) {
        $this->_sectionFactory = $sectionFactory;
        $this->_directoryList = $directoryList;
        $this->_request = $context->getRequest();
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

    public function isHomepage()
    {
        if ($this->_request->getFullActionName() == 'cms_index_index') {
            return true;
        }
        
        return false;
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

    /**
     * get current section
     *
     * @return Object
     */
    public function getSection()
    {
        $sectionObject = $this->_sectionFactory->create();
        $sectionObject->load($this->getIdentifier());
        if($sectionObject->getId() && $sectionObject->getIsActive())
            return $sectionObject;
        return null;
    }

    /**
     * set scrollable
     *
     * @return Object
     */
    public function setScrollable($scrollable)
    {
        $this->_scrollable = $scrollable;
        return $this;
    }

    /**
     * get scrollable
     *
     * @return boolean
     */
    public function getScrollable()
    {
        return $this->_scrollable;
    }
}
