<?php

namespace Ktpl\Blog\Model\ResourceModel\Tag;

/**
 * Blog tag collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Constructor
     * Configures collection
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Ktpl\Blog\Model\Tag', 'Ktpl\Blog\Model\ResourceModel\Tag');
    }

}
