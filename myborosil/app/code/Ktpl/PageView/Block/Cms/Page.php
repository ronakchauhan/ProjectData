<?php

namespace Ktpl\PageView\Block\Cms;

/**
 * Cms page content block
 */
class Page extends \Magento\Cms\Block\Page
{
	/**
     * Prepare global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $page = $this->getPage();
        $this->_addBreadcrumbs($page);
        $this->pageConfig->addBodyClass('cms-' . $page->getIdentifier());
        $metaTitle = $page->getMetaTitle();
        $this->pageConfig->getTitle()->set($metaTitle ? $metaTitle : $page->getTitle());
        $this->pageConfig->setKeywords($page->getMetaKeywords());
        $this->pageConfig->setDescription($page->getMetaDescription());

        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            // Setting empty page title if content heading is absent
            $cmsTitle = $page->getContentHeading() ?: ' ';
            $pageMainTitle->setPageTitle($this->escapeHtml($cmsTitle));
        }

        /* CUSTOM CODE */
        if($this->getPage()->getEnableBlockLayout() && $this->getPage()->getIsRemoveContent())
        	$this->getLayout()->unsetElement('page.main.title');

        $blockLayoutRenderer = $this->getLayout()->getBlock('block.layout.before.renderer');
        if($blockLayoutRenderer)
            $blockLayoutRenderer->setPage($page);

        $blockLayoutRenderer = $this->getLayout()->getBlock('block.layout.after.renderer');
        if($blockLayoutRenderer)
            $blockLayoutRenderer->setPage($page);

        $scrollifyLayoutRenderer = $this->getLayout()->getBlock('ktpl.pageview.scrollify');
        if($scrollifyLayoutRenderer)
            $scrollifyLayoutRenderer->setPage($page);

        /* CUSTOM CODE */

        return parent::_prepareLayout();
    }

	/**
     * Prepare HTML content
     *
     * @return string
     */
    protected function _toHtml()
    {
        $html = $this->getChildHtml('block.layout.before.renderer');

        if(!$this->getPage()->getEnableBlockLayout() || !$this->getPage()->getIsRemoveContent())
            $html .= $this->_filterProvider->getPageFilter()->filter($this->getPage()->getContent());

        $html .= $this->getChildHtml('block.layout.after.renderer');

        if($this->getPage()->getScrollable())
            $html .= $this->getChildHtml('ktpl.pageview.scrollify');

        return $html;
    }
}