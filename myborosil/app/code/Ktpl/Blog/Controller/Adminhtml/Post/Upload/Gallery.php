<?php

namespace Ktpl\Blog\Controller\Adminhtml\Post\Upload;

use Ktpl\Blog\Controller\Adminhtml\Upload\Image\Action;

/**
 * Blog gallery image upload controller
 */
class Gallery extends Action
{
    /**
     * File key
     *
     * @var string
     */
    protected $_fileKey = 'image';

    /**
     * Check admin permissions for this controller
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ktpl_Blog::post');
    }

}
