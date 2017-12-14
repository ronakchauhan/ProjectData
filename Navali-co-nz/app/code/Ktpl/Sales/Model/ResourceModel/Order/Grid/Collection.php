<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Sales\Model\ResourceModel\Order\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;

/**
 * Order grid collection
 */
class Collection extends \Magento\Sales\Model\ResourceModel\Order\Grid\Collection
{
    protected function _initSelect()
	{
	    $this->addFilterToMap('created_at', 'main_table.created_at');

	    parent::_initSelect();
	}
    protected function _renderFiltersBefore() 
    {
        $joinTable = $this->getTable('sales_shipment_track');

        $this->getSelect()->JoinLeft($joinTable.' as ordertable','main_table.entity_id = ordertable.order_id', array('track_number', 'tacking_created_at'=> 'ordertable.track_number'));

        $this->getSelect()->group('main_table.entity_id');

        parent::_renderFiltersBefore();
   }
}
