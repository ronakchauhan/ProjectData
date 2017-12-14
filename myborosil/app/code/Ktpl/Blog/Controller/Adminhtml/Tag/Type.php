<?php

namespace Ktpl\Blog\Controller\Adminhtml\Tag;

/**
 * Admin blog tag edit controller
 */
class Type extends \Ktpl\Blog\Controller\Adminhtml\Actions
{
    /**
     * Form session key
     * @var string
     */
    protected $_formSessionKey  = 'blog_tag_type_form_data';

    /**
     * Allowed Key
     * @var string
     */
    protected $_allowedKey      = 'Ktpl_Blog::post';

    /**
     * Model class name
     * @var string
     */
    protected $_modelClass      = 'Ktpl\Blog\Model\Tag\Type';

    /**
     * Active menu key
     * @var string
     */
    protected $_activeMenu      = 'Ktpl_Blog::post';
}
