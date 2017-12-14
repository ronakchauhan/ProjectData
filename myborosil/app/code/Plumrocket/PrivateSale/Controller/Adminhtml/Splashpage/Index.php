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

class Index extends \Plumrocket\PrivateSale\Controller\Adminhtml\Splashpage
{

	/**
	 * {@inheritdoc}
	 */
	public function execute()
	{
		$this->splashpage->setAdmin(true);
 		if ($this->getRequest()->getParam('store') != null) {
 			$this->splashpage->setStoreId($this->getRequest()->getParam('store'));
 		}

 		$this->coreRegistry->register('current_model', $this->splashpage);


		$this->_view->loadLayout();
 		$this->_addBreadcrumb(__('Manage Splash Page'), __('Manage Splash Page'));
 		$this->_view->getPage()->getConfig()->getTitle()->prepend(__('Splash Page'));
        $this->_view->renderLayout();
	}
}
