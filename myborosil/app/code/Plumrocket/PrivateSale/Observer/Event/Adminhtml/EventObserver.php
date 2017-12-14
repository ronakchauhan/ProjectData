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

namespace Plumrocket\PrivateSale\Observer\Event\Adminhtml;

use Magento\Framework\Event\ObserverInterface;

class EventObserver implements ObserverInterface
{

    /**
     * Indexer factory
     * @var \Plumrocket\PrivateSale\Model\IndexerFactory
     */
    protected $indexerFactory;

    /**
     * Event
     * @var \Plumrocket\PrivateSale\Model\Event
     */
    protected $event;

    /**
     * Request
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * Helper
     * @var \Plumrocket\PrivateSale\Helper\Data
     */
    protected $helper;

    /**
     * Category Factory
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;

    /**
     * Constructor
     * @param \Plumrocket\PrivateSale\Model\Event          $event
     * @param \Plumrocket\PrivateSale\Model\IndexerFactory $indexerFactory
     * @param \Magento\Framework\App\Request\Http          $request
     * @param \Plumrocket\PrivateSale\Helper\Data          $helper
     */
    public function __construct(
        \Plumrocket\PrivateSale\Model\Event $event,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Plumrocket\PrivateSale\Model\IndexerFactory $indexerFactory,
        \Magento\Framework\App\Request\Http $request,
        \Plumrocket\PrivateSale\Helper\Data $helper
    ) {
        $this->request = $request;
        $this->event = $event;
        $this->categoryFactory = $categoryFactory;
        $this->indexerFactory = $indexerFactory;
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

    }
}
