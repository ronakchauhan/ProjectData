<?php

namespace Ktpl\Blog\Controller\Adminhtml;

/**
 * Admin blog category edit controller
 */
class Category extends Actions
{
	/**
	 * Form session key
	 * @var string
	 */
    protected $_formSessionKey  = 'blog_category_form_data';

    /**
     * Allowed Key
     * @var string
     */
    protected $_allowedKey      = 'Ktpl_Blog::category';

    /**
     * Model class name
     * @var string
     */
    protected $_modelClass      = 'Ktpl\Blog\Model\Category';

    /**
     * Active menu key
     * @var string
     */
    protected $_activeMenu      = 'Ktpl_Blog::category';

    /**
     * Status field name
     * @var string
     */
    protected $_statusField     = 'is_active';
}