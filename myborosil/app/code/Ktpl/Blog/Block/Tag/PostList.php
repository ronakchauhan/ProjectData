<?php

namespace Ktpl\Blog\Block\Tag;

use Magento\Store\Model\ScopeInterface;

/**
 * Blog tag posts list
 */
class PostList extends \Ktpl\Blog\Block\Post\PostList
{
    /**
     * Prepare posts collection
     *
     * @return void
     */
    protected function _preparePostCollection()
    {
        parent::_preparePostCollection();
        if ($tag = $this->getTag()) {
            $this->_postCollection->addTagFilter($tag);
        }
    }

    /**
     * Retrieve tag instance
     *
     * @return \Ktpl\Blog\Model\Tag
     */
    public function getTag()
    {
        return $this->_coreRegistry->registry('current_blog_tag');
    }

    /**
     * Preparing global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        if ($tag = $this->getTag()) {
            $this->_addBreadcrumbs($tag->getTitle(), 'blog_tag');
            $this->pageConfig->addBodyClass('blog-tag-' . $tag->getIdentifier());
            $this->pageConfig->getTitle()->set($tag->getTitle());
            $this->pageConfig->addRemotePageAsset(
                $tag->getTagUrl(),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
            $this->pageConfig->setRobots('NOINDEX,FOLLOW');
        }

        return parent::_prepareLayout();
    }

}
