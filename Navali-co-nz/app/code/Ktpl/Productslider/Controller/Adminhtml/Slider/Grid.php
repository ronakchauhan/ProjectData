<?php
/**
 * Copyright © 2016 ktpl (http://www.ktpl.co/) All rights reserved.
 */

namespace Ktpl\Productslider\Controller\Adminhtml\Slider;

class Grid extends \Ktpl\Productslider\Controller\Adminhtml\Slider
{
    /**
     * Prevent entire page loading
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}