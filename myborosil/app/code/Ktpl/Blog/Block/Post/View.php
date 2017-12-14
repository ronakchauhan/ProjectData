<?php

namespace Ktpl\Blog\Block\Post;

use Magento\Store\Model\ScopeInterface;

/**
 * Blog post view
 */
class View extends AbstractPost
{
    /**
     * Preparing global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $post = $this->getPost();
        if ($post) {
            $this->_addBreadcrumbs($post->getTitle(), 'blog_post');
            $this->pageConfig->addBodyClass('blog-post-' . $post->getIdentifier());
            $this->pageConfig->getTitle()->set($post->getMetaTitle());
            $this->pageConfig->setKeywords($post->getMetaKeywords());
            $this->pageConfig->setDescription($post->getMetaDescription());
            $this->pageConfig->addRemotePageAsset(
                $post->getPostUrl(),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );

            $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
            if ($pageMainTitle) {
                $pageMainTitle->setPageTitle(
                    $this->escapeHtml($post->getTitle())
                );
            }
        }

        return parent::_prepareLayout();
    }

    /**
     * Prepare breadcrumbs
     *
     * @param  string $title
     * @param  string $key
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function _addBreadcrumbs($title = null, $key = null)
    {
        if ($this->_scopeConfig->getValue('web/default/show_cms_breadcrumbs', ScopeInterface::SCOPE_STORE)
            && ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs'))
        ) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );

            $blogTitle = $this->_scopeConfig->getValue(
                'mfblog/index_page/title',
                ScopeInterface::SCOPE_STORE
            );
            $breadcrumbsBlock->addCrumb(
                'blog',
                [
                    'label' => __($blogTitle),
                    'title' => __($blogTitle),
                    'link' => $this->_url->getBaseUrl()
                ]
            );
            $breadcrumbsBlock->addCrumb($key, [
                'label' => $title ,
                'title' => $title
            ]);
        }
    }

    /**
     * Retrieve filtered content
     *
     * @return string
     */
    public function getShortDesc()
    {   
        $shortDesc = $this->getPost()->getShortDesc();
        $key = 'short_desc_filtered_content';
        if (!$this->hasData($key)) {
            $content = $this->_filterProvider->getPageFilter()->filter(
                $shortDesc
            );
            $this->setData($key, $content);
        }
        return $this->getData($key);
    }

    /**
     * Retrieve filtered content
     *
     * @return string
     */
    public function getLeftDesc()
    {   
        $leftDesc = $this->getPost()->getLeftDesc();
        $key = 'left_Desc_filtered_content';
        if (!$this->hasData($key)) {
            $content = $this->_filterProvider->getPageFilter()->filter(
                $leftDesc
            );
            $this->setData($key, $content);
        }
        return $this->getData($key);
    }


    /**
     * Retrieve filtered content
     *
     * @return string
     */
    public function getRightDesc()
    {   
        $rightDesc = $this->getPost()->getRightDesc();
        $key = 'right_desc_filtered_content';
        if (!$this->hasData($key)) {
            $content = $this->_filterProvider->getPageFilter()->filter(
                $rightDesc
            );
            $this->setData($key, $content);
        }
        return $this->getData($key);
    }

    /**
     * Retrieve filtered content
     *
     * @return string
     */
    public function getVerticleDescOne()
    {   
        $verticleDescOne = $this->getPost()->getVerticleDescOne();
        $key = 'vericle_desc_one_filtered_content';
        if (!$this->hasData($key)) {
            $content = $this->_filterProvider->getPageFilter()->filter(
                $verticleDescOne
            );
            $this->setData($key, $content);
        }
        return $this->getData($key);
    }

    /**
     * Retrieve filtered content
     *
     * @return string
     */
    public function getVerticleDescTwo()
    {   
        $verticleDescTwo = $this->getPost()->getVerticleDescTwo();
        $key = 'vericle_desc_two_filtered_content';
        if (!$this->hasData($key)) {
            $content = $this->_filterProvider->getPageFilter()->filter(
                $verticleDescTwo
            );
            $this->setData($key, $content);
        }
        return $this->getData($key);
    }

    /**
     * Retrieve filtered content
     *
     * @return string
     */
    public function getVerticleDescThree()
    {   
        $verticleDescThree = $this->getPost()->getVerticleDescThree();
        $key = 'vericle_desc_three_filtered_content';
        if (!$this->hasData($key)) {
            $content = $this->_filterProvider->getPageFilter()->filter(
                $verticleDescThree
            );
            $this->setData($key, $content);
        }
        return $this->getData($key);
    }

}
