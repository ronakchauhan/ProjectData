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

namespace Plumrocket\PrivateSale\Controller\Adminhtml\Splashpage;

class Save extends \Plumrocket\PrivateSale\Controller\Adminhtml\Splashpage
{
	/**
	 * {@inheritdoc}
	 */
	public function execute()
	{
		try {
			if ($this->getRequest()->getParam('is_new_files', 0) == "1") {
	      		$this->image->loadFiles();
			}

	      	$data = $this->getRequest()->getPost();

	      	unset($data['form_key']);


	      	$storeId = $this->getRequest()->getParam('store');

	      	if ($storeId) {
	      		$scope = 'stores';
	      	} else {
	      		$scope = 'default';
	      		$storeId = 0;
	      	}

	      	$data = $this->jsonHelper->jsonEncode($data);
	      	$this->config->saveConfig(\Plumrocket\PrivateSale\Model\Splashpage::CONFIG_PATH, $data, $scope, $storeId);

	      	$images = $this->getRequest()->getPost('images');
	      	if (count($images)) {
	      		$this->image->saveImages($images);
	      	}

	      	$this->appConfig->reinit();
	      	$this->messageManager->addSuccess( __('You saved the configuration.') );

	      	$params = [];
	      	if ($storeId) {
	      		$params = ['store' => $storeId];
	      	}


		    $this->_redirect('*/*', $params);
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
		}


	}
}
