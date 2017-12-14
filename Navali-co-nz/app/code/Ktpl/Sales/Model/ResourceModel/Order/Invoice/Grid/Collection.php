<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ktpl\Sales\Model\ResourceModel\Order\Invoice\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Sales\Ui\Component\DataProvider\Document;
use Psr\Log\LoggerInterface as Logger;

class Collection extends \Magento\Sales\Model\ResourceModel\Order\Invoice\Grid\Collection
{

    protected function _initSelect()
	{
	    $this->addFilterToMap('created_at', 'main_table.created_at');
	    $this->addFilterToMap('status', 'main_table.status');

	    parent::_initSelect();
	}
    protected function _renderFiltersBefore() {
        $joinTable = $this->getTable('sales_shipment_track');

        $this->getSelect()->JoinLeft($joinTable.' as ordertable','main_table.order_id = ordertable.order_id', array('track_number'));
        parent::_renderFiltersBefore();
   }
}
