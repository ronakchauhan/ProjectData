<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Sales\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Framework\Escaper;

/**
 * Cart crosssell list
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class OrderGridItems extends Column
{
    /** @var \Magento\Sales\Api\Data\OrderInterface $order **/
	protected $_orderCollectionFactory;

	protected $_objectManager;

	/**
     * @var Escaper
     */
    protected $escaper;

	public function __construct(
		ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Escaper $escaper,
    	CollectionFactory $orderCollection,
    	\Magento\Framework\ObjectManagerInterface $objectManager,
    	array $components = [],
        array $data = []
   	) {
	    $this->_orderCollectionFactory = $orderCollection;
	    $this->_objectManager = $objectManager;
	    $this->escaper = $escaper;
	    
	    parent::__construct($context, $uiComponentFactory, $components, $data);
	}

	public function getOrderItemNames($id)
	{
		// $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$order = $this->_objectManager->create('\Magento\Sales\Model\Order')->loadByIncrementId($id);
		$trackNumber = array();
		$items = "";
		$i = 0;
		foreach ($order->getAllItems() as $_item) {
           $itemName = $_item->getName();
        }
		
		if ($i > 1) 
        {
        	$items = $this->escaper->escapeHtml(implode(",", $itemName));
        }
        else
        {
        	$items = $this->escaper->escapeHtml($itemName);
        }

		return $items;
	}

    public function prepareDataSource(array $dataSource)
	{	   
	    if (isset($dataSource['data']['items'])) {
       
	        foreach ($dataSource['data']['items'] as &$item) {
	        	$item['items'] = $this->getOrderItemNames($item['increment_id']);
	        }	        
	        
	    }

	    return $dataSource;
	}

}
