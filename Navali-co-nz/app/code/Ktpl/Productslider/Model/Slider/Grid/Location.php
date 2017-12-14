<?php
/**
 * Copyright © 2016 ktpl (http://www.ktpl.co/) All rights reserved.
 */

namespace Ktpl\Productslider\Model\Slider\Grid;

/**
 * To option slider locations array
 * @return array
 */
class Location implements \Magento\Framework\Option\ArrayInterface{

    public function toOptionArray(){
        return \Ktpl\Productslider\Model\Productslider::getSliderGridLocations();
    }
}