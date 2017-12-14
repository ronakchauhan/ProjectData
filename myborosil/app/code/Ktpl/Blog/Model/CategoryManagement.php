<?php

namespace Ktpl\Blog\Model;

/**
 * Category management model
 */
class CategoryManagement extends AbstractManagement
{
    /**
     * @var \Ktpl\Blog\Model\CategoryFactory
     */
    protected $_itemFactory;

    /**
     * Initialize dependencies.
     *
     * @param \Ktpl\Blog\Model\CategoryFactory $categoryFactory
     */
    public function __construct(
        \Ktpl\Blog\Model\CategoryFactory $categoryFactory
    ) {
        $this->_itemFactory = $categoryFactory;
    }

}
