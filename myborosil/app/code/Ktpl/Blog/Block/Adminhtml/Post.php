<?php

namespace Ktpl\Blog\Block\Adminhtml;

/**
 * Admin blog post
 */
class Post extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_post';
        $this->_blockGroup = 'Ktpl_Blog';
        $this->_headerText = __('Post');
        $this->_addButtonLabel = __('Add New Post');
        parent::_construct();
    }
}
