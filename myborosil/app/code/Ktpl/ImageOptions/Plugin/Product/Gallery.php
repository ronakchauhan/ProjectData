<?php

namespace Ktpl\ImageOptions\Plugin\Product;

class Gallery
{
    public function afterCreateBatchBaseSelect(
        \Magento\Catalog\Model\ResourceModel\Product\Gallery $subject,
        \Magento\Framework\DB\Select $select
    ) {
        $select->columns('isbigimage');
        $select->columns('partsimage');
        $select->columns('hidefromlisting');

        return $select;
    }
}