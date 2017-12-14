<?php

namespace Ktpl\Blog\Block\Author;

use Magento\Store\Model\ScopeInterface;

/**
 * Blog author posts list
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
        if ($author = $this->getAuthor()) {
            $this->_postCollection->addAuthorFilter($author);
        }
    }

    /**
     * Retrieve author instance
     *
     * @return \Ktpl\Blog\Model\Author
     */
    public function getAuthor()
    {
        return $this->_coreRegistry->registry('current_blog_author');
    }

    /**
     * Preparing global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        if ($author = $this->getAuthor()) {
            $this->_addBreadcrumbs($author->getTitle(), 'blog_author');
            $this->pageConfig->addBodyClass('blog-author-' . $author->getIdentifier());
            $this->pageConfig->getTitle()->set($author->getTitle());
            $this->pageConfig->addRemotePageAsset(
                $author->getAuthorUrl(),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
            $this->pageConfig->setRobots('NOINDEX,FOLLOW');
        }

        return parent::_prepareLayout();
    }

}
