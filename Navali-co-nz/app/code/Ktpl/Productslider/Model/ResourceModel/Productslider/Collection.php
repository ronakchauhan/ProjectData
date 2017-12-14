<?php
/**
 * Copyright © 2016 ktpl (http://www.ktpl.co/) All rights reserved.
 */

namespace Ktpl\Productslider\Model\ResourceModel\Productslider;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    /**
     * Initialize resources
     * @return void
     */
    protected function _construct(){
        $this->_init('Ktpl\Productslider\Model\Productslider','Ktpl\Productslider\Model\ResourceModel\Productslider');
    }

}