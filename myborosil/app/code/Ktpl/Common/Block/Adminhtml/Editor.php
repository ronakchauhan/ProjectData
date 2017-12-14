<?php

namespace Ktpl\Common\Block\Adminhtml;

class Editor extends \Magento\Backend\Block\Template
{
    const BLOCK_TEXT = 'text';
    const BLOCK_BUTTON = 'button';

	/**
     * @var string
     */
    protected $_template = 'Ktpl_Common::editor.phtml';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Directory List
     *
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_directoryList = $directoryList;
        parent::__construct($context, $data);
    }
    
    /**
     * Get Editor Config
     *
     * @return \Ktpl\Common\Model\Editor\Config
     */
    public function getEditorConfig()
    {
        return $this->_coreRegistry->registry('editor_config');
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

    /**
     * get child block html
     *
     * @param $type
     * @return string
     */
    public function getChildBlockHtml($type)
    {
        switch($type)
        {
            case self::BLOCK_TEXT:
                return $this->getLayout()->createBlock('\Ktpl\Common\Block\Adminhtml\Editor\Text')->toHtml();
                break;
            case self::BLOCK_BUTTON:
                return $this->getLayout()->createBlock('\Ktpl\Common\Block\Adminhtml\Editor\Button')->toHtml();
                break;
        }
        return "";
    }

    /**
     * get button html
     *
     * @param $data
     * @return string
     */
    public function getButtonHtml($data)
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData($data);

        return $button->toHtml();
    }
}