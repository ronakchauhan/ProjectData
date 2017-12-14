<?php

namespace Ktpl\Blog\Block\Archive;

use Magento\Store\Model\ScopeInterface;

/**
 * Blog archive posts list
 */
class PostList extends \Ktpl\Blog\Block\Post\PostList
{
    /**
     * Prepare posts collection
     * @return \Ktpl\Blog\Model\ResourceModel\Post\Collection
     */
    protected function _preparePostCollection()
    {
        parent::_preparePostCollection();
        $this->_postCollection->addArchiveFilter(
            $this->getYear(),
            $this->getMonth()
        );
    }

    /**
     * Get archive month
     * @return string
     */
    public function getMonth()
    {
        return (int)$this->_coreRegistry->registry('current_blog_archive_month');
    }

    /**
     * Get archive year
     * @return string
     */
    public function getYear()
    {
        return (int)$this->_coreRegistry->registry('current_blog_archive_year');
    }

    /**
     * Preparing global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $title = $this->_getTitle();
        $this->_addBreadcrumbs($title, 'blog_search');
        $this->pageConfig->getTitle()->set($title);
        $this->pageConfig->addRemotePageAsset(
            $this->_url->getUrl(
                $this->getYear() . '-' . str_pad($this->getMonth(), 2, '0', STR_PAD_LEFT),
                \Ktpl\Blog\Model\Url::CONTROLLER_ARCHIVE
            ),
            'canonical',
            ['attributes' => ['rel' => 'canonical']]
        );
        $this->pageConfig->setRobots('NOINDEX,FOLLOW');

        return parent::_prepareLayout();
    }

    /**
     * Retrieve title
     * @return string
     */
    protected function _getTitle()
    {
        $time = strtotime($this->getYear().'-'.$this->getMonth().'-01');
        return sprintf(
            __('Monthly Archives: %s %s'),
            __(date('F', $time)), date('Y', $time)
        );
    }

}
