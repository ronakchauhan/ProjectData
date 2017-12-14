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
class BillingName extends Column
{
    
	/**
     * @var Escaper
     */
    protected $escaper;

	public function __construct(
		ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Escaper $escaper,
    	array $components = [],
        array $data = []
   	) {
	    $this->escaper = $escaper;
	    
	    parent::__construct($context, $uiComponentFactory, $components, $data);
	}

    public function prepareDataSource(array $dataSource)
	{	   
	    if (isset($dataSource['data']['items'])) {
	        
	        foreach ($dataSource['data']['items'] as &$item) {
	        	$name = '';
	        	// echo"<pre/>"; print_r($dataSource['data']['items']);exit;
	        	$name .= $this->escaper->escapeHtml($item['billing_name']) . "<br/>";
	        	$name .= $this->escaper->escapeHtml($item['billing_address']) . "<br/>";
	        	$item['billing_name'] =  $name;
                
	        }
	    }
	    // echo"<pre/>"; print_r($dataSource['data']['items']);exit;
	    return $dataSource;
	}

}
