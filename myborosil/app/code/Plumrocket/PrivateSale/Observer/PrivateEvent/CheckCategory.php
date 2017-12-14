<?php

namespace Plumrocket\PrivateSale\Observer\PrivateEvent;


use Magento\Framework\Event\ObserverInterface;

class CheckCategory implements ObserverInterface
{
	/**
	 * Registry
	 * @var Magento\Framework\Registry
	 */
	protected $registry;

	/**
	 * Private event
	 * @var Plumrocket\PrivateSale\Model\PrivateEvent
	 */
	protected $privateEvent;

	/**
	 * Constructor
	 * @param \Plumrocket\PrivateSale\Model\PrivateEvent $privateEvent
	 * @param \Magento\Framework\Registry                $registry
	 */
	public function __construct(
		\Plumrocket\PrivateSale\Model\PrivateEvent $privateEvent,
        \Magento\Framework\Registry $registry
	) {
		$this->privateEvent = $privateEvent;
		$this->registry = $registry;
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$category = $this->registry->registry('current_category');
        if (!$category || !$category->getId()) {
            return $observer;
        }

		if ($this->privateEvent->isCategoryPrivateEvent($category)) {

			$redirectUrl = $this->privateEvent->getRedirectUrl($category);

			$data = $observer->getEvent()->getData();
			$data['controller_action']->getResponse()->setRedirect($redirectUrl);
		}
        return $observer;
	}
}
