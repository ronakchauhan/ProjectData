<?php
/**
 * Copyright © 2016 ktpl (http://www.ktpl.co/) All rights reserved.
 */

namespace Ktpl\Productslider\Model\Slider\Grid;

class Type implements \Magento\Framework\Data\OptionSourceInterface{

    /**
     * To option slider types array
     * @return array
     */
    public function toOptionArray(){
        return \Ktpl\Productslider\Model\Productslider::getSliderTypeArray();
    }
}