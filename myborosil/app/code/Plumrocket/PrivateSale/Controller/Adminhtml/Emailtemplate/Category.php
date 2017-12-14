<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket Private Sales and Flash Sales v4.x.x
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


namespace Plumrocket\PrivateSale\Controller\Adminhtml\Emailtemplate;

class Category extends \Plumrocket\PrivateSale\Controller\Adminhtml\Emailtemplate
{
	/**
	 * {@inheritdoc}
	 */
	public function execute()
	{
		$_request 	= $this->getRequest();
		$storeId 	= $_request->getParam('store_id');
		$date		= $_request->getParam('date');

		if (!$storeId) {
			$storeId = 0;
		}

		$_result = array();
		if ($date){
			$_categories	= $this->_getModel()->loadCategoriesByCriteria($date, $storeId);
			$_result		= $this->_getModel()->categoriesToOptions($_categories);
		}

	    $this->getResponse()->setBody(json_encode(array(
	    	'count'			=> count($_result),
	    	'categories' 	=> $_result,
	    )));
	}

}
