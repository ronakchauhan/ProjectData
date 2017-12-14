<?php
/**
 * Copyright © 2016 ktpl (http://www.ktpl.co/) All rights reserved.
 */

namespace Ktpl\Productslider\Model\Slider\Grid;

class Status implements \Magento\Framework\Option\ArrayInterface {

    /**
     * To option slider statuses array
     * @return array
     */
    public function toOptionArray(){
        return \Ktpl\Productslider\Model\Productslider::getStatusArray();
    }
}