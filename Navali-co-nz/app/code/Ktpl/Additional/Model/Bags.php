<?php

namespace Ktpl\Additional\Model;

// use Ktpl\Additional\Api\Data\BagsInterface;

class Bags extends \Magento\Framework\Model\AbstractModel /*implements BagsInterface*/
{ 
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ktpl\Additional\Model\ResourceModel\Bags');
    }
}