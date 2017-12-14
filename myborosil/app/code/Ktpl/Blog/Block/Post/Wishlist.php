<?php

namespace Ktpl\Blog\Block\Post;

use Magento\Store\Model\ScopeInterface;

/**
 * Blog post list block
 */
class Wishlist extends \Ktpl\Blog\Block\Post\PostList\AbstractList
{
    /**
     * Block template file
     * @var string
     */
    protected $_defaultToolbarBlock = 'Ktpl\Blog\Block\Post\PostList\Toolbar';

    /**
     * Preparing global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {          
        $page = $this->_request->getParam(
            \Ktpl\Blog\Block\Post\PostList\Toolbar::PAGE_PARM_NAME
        );

        if ($page > 1) {
            $this->pageConfig->setRobots('NOINDEX,FOLLOW');
        }

        return parent::_prepareLayout();
    }

    /**
     * Retrieve post html
     * @param  \Ktpl\Blog\Model\Post $post
     * @return string
     */
    public function getPostHtml($post)
    {
        return $this->getChildBlock('blog.posts.list.item')->setPost($post)->toHtml();
    }

    /**
     * Retrieve Toolbar Block
     * @return \Ktpl\Blog\Block\Post\PostList\Toolbar
     */
    public function getToolbarBlock()
    {
        $blockName = $this->getToolbarBlockName();

        if ($blockName) {
            $block = $this->getLayout()->getBlock($blockName);
            if ($block) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, uniqid(microtime()));
        return $block;
    }

    /**
     * Retrieve Toolbar Html
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Before block to html
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $toolbar = $this->getToolbarBlock();


        // called prepare sortable parameters
        $collection = $this->getPostCollection();

        $collection->getSelect()->join(
            ['wishlist' => 'ktpl_blog_post_wishlist'],
            'main_table.post_id = wishlist.post_id'
        );

        $collection->getSelect()->join(
            ['customer' => 'customer_entity'],
            'wishlist.customer_id = customer.entity_id'
        );
            
        //echo"<pre/>"; print_r($collection->getData());exit;
        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);
        $this->setChild('toolbar', $toolbar);

        return parent::_beforeToHtml();
    }
}
