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

namespace Plumrocket\PrivateSale\Observer\Event;

use Magento\Framework\Event\ObserverInterface;

class EventObserver implements ObserverInterface
{
	/**
	 * Event
	 * @var \Plumrocket\PrivateSale\Model\Event
	 */
	protected $event;

	/**
	 * Data helper
	 * @var \Plumrocket\PrivateSale\Helper\Data
	 */
	protected $helper;

	/**
	 * Preview helper
	 * @var \Plumrocket\PrivateSale\Helper\Preview
	 */
	protected $previewHelper;

	/**
	 * Resource collection
	 * @var \Magento\Framework\App\ResourceConnection
	 */
	protected $resource;

	/**
	 * Contructor
	 * @param \Plumrocket\PrivateSale\Model\Event       $event
	 * @param \Plumrocket\PrivateSale\Helper\Data       $helper
	 * @param \Plumrocket\PrivateSale\Helper\Preview    $previewHelper
	 * @param \Magento\Framework\App\ResourceConnection $resource
	 */
	public function __construct(
		\Plumrocket\PrivateSale\Model\Event $event,
		\Plumrocket\PrivateSale\Helper\Data $helper,
		\Plumrocket\PrivateSale\Helper\Preview $previewHelper,
		\Magento\Framework\App\ResourceConnection $resource
	) {
		$this->event = $event;
		$this->resource = $resource;
		$this->helper = $helper;
		$this->previewHelper = $previewHelper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{

	}

    /**
     * Allow process logic of privatesale
     * @return boolean
     */
	protected function _allowProcess()
	{
		return ($this->helper->moduleEnabled() || $this->previewHelper->isAllowPreview());
	}

	/**
     * Apply event if needed
     * @param  Magento\Catalog\Model\Product $product
     * @return $this
     */
    protected function _apply($product)
    {
        $action = $this->event->checkProduct($product);

        if ($action == \Plumrocket\PrivateSale\Model\Config\Source\Eventend::NOT_FOUND) {
            $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED);
        } elseif ($action == \Plumrocket\PrivateSale\Model\Config\Source\Eventend::DISABLE_ADD_TO_CART) {
            $product->setIsSalable(0);
            $product->setPrivatesaleExpiredFlag(true);
        }

        return $this;
    }
}
