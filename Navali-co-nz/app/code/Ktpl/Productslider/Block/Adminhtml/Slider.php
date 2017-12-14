<?php
/**
 * Copyright © 2016 ktpl (http://www.ktpl.co/) All rights reserved.
 */

namespace Ktpl\Productslider\Block\Adminhtml;

class Slider extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Modify header & button labels
     *
     * @return void
     */
    protected function _construct(){
        $this->_blockGroup = 'Ktpl_Productslider';
        $this->_controller = 'adminhtml';
        $this->_headerText = 'Slider';
        $this->_addButtonLabel = __('Create New Slider');
        parent::_construct();
    }

}