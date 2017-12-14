<?php

namespace Ktpl\HeaderView\Block\Theme\Html\Header;

class TopLinks extends \Magento\Framework\View\Element\AbstractBlock
{
	/**
     * get link array
     *
     * @return array
     */
	protected function _getLinks()
    {
        $sortOrder = $this->_scopeConfig->getValue(
        	'header/top_links/sort_order', 
        	\Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $sortOrder = json_decode($sortOrder, true); $sortArray = [];
        foreach($sortOrder as $sort)
            $sortArray[$sort['sort_order']] = $sort['element_code'];

        krsort($sortArray);

        return $sortArray;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $sortArray = $this->_getLinks();

        $sortHtmlArray = [];
        foreach($sortArray as $key => $block)
            $sortHtmlArray[] = $this->getChildHtml($block);

        return implode("", $sortHtmlArray);
    }
}