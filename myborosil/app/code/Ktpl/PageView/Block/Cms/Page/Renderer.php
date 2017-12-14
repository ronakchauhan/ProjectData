<?php

namespace Ktpl\PageView\Block\Cms\Page;

class Renderer extends \Magento\Framework\View\Element\AbstractBlock
{
    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $_blockFactory;

    /**
     * @var \Zend_Filter_Interface
     */
    protected $templateProcessor;

     /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Zend_Filter_Interface $templateProcessor,
        array $data = []
    ) {
        $this->_blockFactory = $blockFactory;
        $this->templateProcessor = $templateProcessor;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve block information
     *
     * @return string[]
     */
    protected function _getBlocks()
    {
        $data = $this->getPage()->getBlockLayout();
        
        $blockArray = [];
        if(isset($data) && $data != "")
        {
            $dataLayout = json_decode($data, true);
            foreach($dataLayout as $layout)
                $blockArray[$layout['sort_order']] = [
                    'type' => $layout['select_type'], 
                    'block' => $layout['select_block'],
                    'scrollable' => ((isset($layout['scrollable']) && $this->getPage()->getScrollable())? $layout['scrollable']: false)
                ];
        }
        ksort($blockArray, SORT_NUMERIC);

        return $blockArray;
    }

    /**
     * Retrieve block content
     *
     * @param string $identifier
     * @return string
     */
    protected function _getBlockHtml($identifier)
    {
        $blockObject = $this->_blockFactory->create();
        $blockObject->load($identifier);
        if($blockObject->getId())
            return $this->templateProcessor->filter($blockObject->getContent());

        return '';
    }

    /**
     * Retrieve section content
     *
     * @param string $identifier
     * @return string
     */
    protected function _getSectionHtml($identifier, $scrollable)
    {
        return $this->getLayout()->createBlock('\Ktpl\SectionView\Block\View')
            ->setIdentifier($identifier)
            ->setScrollable($scrollable)
            ->toHtml();
        
        return '';
    }

    /**
     * Prepare HTML content
     *
     * @return string
     */
    protected function _toHtml()
    {
        if(!$this->getPage()->getEnableBlockLayout() || ($this->getPage()->getBlockLayoutPosition() != $this->getBlockLayoutPosition()))
            return "";

        $blocks = $this->_getBlocks();

        $blockHtmlArray = [];
        foreach($blocks as $key => $value)
        {
            if($value['type'] == \Ktpl\PageView\Block\Adminhtml\Options::TYPE_BLOCK)
                $blockHtmlArray[] = $this->_getBlockHtml($value['block']);
            else
                $blockHtmlArray[] = $this->_getSectionHtml($value['block'], $value['scrollable']);
        }

        return implode("", $blockHtmlArray);
    }
}
