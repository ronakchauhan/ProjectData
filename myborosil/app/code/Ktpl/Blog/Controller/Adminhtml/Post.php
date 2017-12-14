<?php

namespace Ktpl\Blog\Controller\Adminhtml;

/**
 * Admin blog post edit controller
 */
class Post extends Actions
{
	/**
	 * Form session key
	 * @var string
	 */
    protected $_formSessionKey  = 'blog_post_form_data';

    /**
     * Allowed Key
     * @var string
     */
    protected $_allowedKey      = 'Ktpl_Blog::post';

    /**
     * Model class name
     * @var string
     */
    protected $_modelClass      = 'Ktpl\Blog\Model\Post';

    /**
     * Active menu key
     * @var string
     */
    protected $_activeMenu      = 'Ktpl_Blog::post';

    /**
     * Status field name
     * @var string
     */
    protected $_statusField     = 'is_active';

}
