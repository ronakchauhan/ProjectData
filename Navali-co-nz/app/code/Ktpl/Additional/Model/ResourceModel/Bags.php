<?php
/**
 * Copyright © 2016 AionNext Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Additional\Model\ResourceModel;

/**
 * Aion Test resource model
 */
class Bags extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('bag_query_request', 'entity_id');
    }      
}