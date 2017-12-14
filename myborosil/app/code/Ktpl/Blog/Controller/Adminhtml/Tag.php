<?php

namespace Ktpl\Blog\Controller\Adminhtml;

/**
 * Admin blog tag edit controller
 */
class Tag extends Actions
{
    /**
     * Form session key
     * @var string
     */
    protected $_formSessionKey  = 'blog_tag_form_data';

    /**
     * Allowed Key
     * @var string
     */
    protected $_allowedKey      = 'Ktpl_Blog::post';

    /**
     * Model class name
     * @var string
     */
    protected $_modelClass      = 'Ktpl\Blog\Model\Tag';

    /**
     * Active menu key
     * @var string
     */
    protected $_activeMenu      = 'Ktpl_Blog::post';
}
