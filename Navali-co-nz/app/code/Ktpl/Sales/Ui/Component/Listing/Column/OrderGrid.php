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
class OrderGrid extends Column
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

	public function getOrderTracking($id)
	{
		// $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$order = $this->_objectManager->create('\Magento\Sales\Model\Order')->loadByIncrementId($id);
	    // $collection = $this->_orderCollectionFactory->load('100000001');
		$trackNumber = "";
		foreach ($order->getTracksCollection() as $_track) {
           $trackNumber = $_track->getNumber();
        }
		
		return $trackNumber;
	}

    public function prepareDataSource(array $dataSource)
	{	   
	    if (isset($dataSource['data']['items'])) {
	        
	        foreach ($dataSource['data']['items'] as &$item) {
	        	$trackNumber = $this->getOrderTracking($item['increment_id']);
	            $item[$this->getData('name')] = $this->escaper->escapeHtml($trackNumber);
	        }
	    }
	    //die("DIED");
	    // echo"<pre/>"; print_r($dataSource['data']['items']);exit;
	    return $dataSource;
	}

}
