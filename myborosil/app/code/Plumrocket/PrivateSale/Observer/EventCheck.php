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

namespace Plumrocket\PrivateSale\Observer;

use Magento\Framework\Event\ObserverInterface;

class EventCheck implements ObserverInterface
{

	/**
	 * Data helper
	 * @var \Plumrocket\PrivateSale\Helper\Data
	 */
	protected $helper;

	/**
	 * Request
	 * @var \Magento\Framework\App\Request\Http
	 */
	protected $request;

	/**
	 * Registry
	 * @var \Magento\Framework\Registry
	 */
	protected $registry;

	/**
	 * Event
	 * @var \Plumrocket\PrivateSale\Model\Event
	 */
	protected $event;

	/**
	 * Conturcor
	 * @param \Magento\Framework\App\Request\Http $request
	 * @param \Magento\Framework\Registry         $registry
	 * @param \Plumrocket\PrivateSale\Model\Event   $event
	 * @param \Plumrocket\PrivateSale\Helper\Data   $helper
	 */
	public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $registry,
        \Plumrocket\PrivateSale\Model\Event $event,
		\Plumrocket\PrivateSale\Helper\Data $helper
	) {
		$this->helper = $helper;
		$this->request = $request;
		$this->event = $event;
		$this->registry = $registry;
	}

    /**
     * {@inheritdoc}
     * @param  \Magento\Framework\Event\Observer $observer
     * @return void
     */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        if (!$this->helper->moduleEnabled()) {
            return $observer;
        }

        $fan = $this->request->getFullActionName();

        if (in_array($fan, array('catalog_category_view', 'catalog_product_view'))) {

            $category = $this->registry->registry('current_category');
            $product = $this->registry->registry('current_product');

            $layout = $observer->getLayout();
            if ($fan == 'catalog_category_view') {
                 //If display type for current category eq flash sale homepage, than set template of private sale
                if ($category->getDisplayMode() == \Plumrocket\PrivateSale\Model\Event::DM_HOMEPAGE) {
                     //add handle for homepage
                    $layout->getUpdate()->addHandle('plumrocket_privatesale_homepage_default');
                } elseif ($this->event->isEventActive($category)) {
                    //If enabled events for category add handle to layout
                    $layout->getUpdate()->addHandle('plumrocket_privatesale_category_view');
                }
            } elseif ($fan == 'catalog_product_view' && $this->event->getEventsByProduct($product)->getSize() ) {
                $layout->getUpdate()->addHandle('plumrocket_privatesale_product_view');
            }

        }

        return $this;
	}
}
