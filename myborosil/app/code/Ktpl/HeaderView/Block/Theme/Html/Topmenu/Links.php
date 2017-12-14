<?php

namespace Ktpl\HeaderView\Block\Theme\Html\Topmenu;

class Links extends \Magento\Framework\View\Element\AbstractBlock
{
	/**
     * get link array
     *
     * @return array
     */
	protected function _getLinks()
    {
        $links = $this->_scopeConfig->getValue(
        	'header/navigation/static_links', 
        	\Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
        $linksArray = [];
        if(isset($links) && $links != "")
        {
            $links = unserialize($links);      
            foreach($links as $link)
            {
                if($link["side"] == $this->getSide())
                {
                    $linksArray[] = [
                        'label' => $link['label'],
                        'url' => $this->_urlBuilder->getUrl($link['url_path']),
                        'position' => (isset($link['position']) && trim($link['position']) != ""? $link['position']: '0')
                    ];
                }
            }
        }

        usort($linksArray, function($a, $b) {
            return $a['position'] - $b['position'];
        });

        return $linksArray;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $linksArray = $this->_getLinks();

        $linkHtmlArray = [];
        foreach($linksArray as $link)
        {
            $linkHtmlArray[] = '
            	<li class="' . ($this->_getCurrentUrl() == $link['url']? 'active': '') . ' level0 nav-1 level-top">
			       <a href="' . $link['url'] . '" class="level-top">
			           <span>' .  $link['label'] . '</span>
			       </a>
			    </li>
            ';
        }

        return implode("", $linkHtmlArray);
    }

    /**
     * Get current string
     *
     * @return string
     */
    protected function _getCurrentUrl()
    {
        return $this->_urlBuilder->getCurrentUrl();
    }
}