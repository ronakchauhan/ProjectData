<?php

namespace Plumrocket\PrivateSale\Cron;

class FlushCache
{

	const LAST_FLUSH_DATE_PATH = 'privatesales/cron/last_flush';

	/**
	 * Types of cache, which must be flushed
	 * @var array
	 */
	protected $types = ['block_html' => 1,'full_page' => 1];

	/**
	 * Event
	 * @var \Plumrocket\PrivateSale\Model\Event
	 */
	protected $event;

	/**
	 * Cache type list
	 * @var \Magento\Framework\App\Cache\TypeListInterface
	 */
	protected $cacheTypeList;

	/**
	 * Category factory
	 * @var Magento\Catalog\Model\CategoryFactory
	 */
	protected $categoryFactory;

	/**
	 * Config
	 * @var \Magento\Config\Model\ResourceModel\Config
	 */
	protected $config;

	/**
	 * Data helper
	 * @var \Plumrocket\PrivateSale\Helper\Data
	 */
	protected $dataHelper;

	/**
	 * App config
	 * @var \Magento\Framework\App\Config\ReinitableConfigInterface
	 */
	protected $appConfig;

	/**
	 * Constructor
	 * @param \Plumrocket\PrivateSale\Model\Event                     $event
	 * @param \Plumrocket\PrivateSale\Helper\Data                     $dataHelper
	 * @param \Magento\Framework\App\Cache\TypeListInterface          $cacheTypeList
	 * @param \Magento\Catalog\Model\CategoryFactory                  $categoryFactory
	 * @param \Magento\Framework\App\Config\ReinitableConfigInterface $appConfig
	 * @param \Magento\Config\Model\ResourceModel\Config              $config
	 */
	public function __construct(
		\Plumrocket\PrivateSale\Model\Event $event,
		\Plumrocket\PrivateSale\Helper\Data $dataHelper,
		\Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
		\Magento\Framework\App\Config\ReinitableConfigInterface $appConfig,
		\Magento\Config\Model\ResourceModel\Config $config
	) {
		$this->event = $event;
		$this->categoryFactory = $categoryFactory;
		$this->cacheTypeList = $cacheTypeList;
		$this->dataHelper = $dataHelper;
		$this->config = $config;
		$this->appConfig = $appConfig;
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute()
	{
		if ($this->dataHelper->moduleEnabled()) {
			$lastFlush = $this->dataHelper->getConfig(self::LAST_FLUSH_DATE_PATH);
			$currentTime = $this->event->getCurrentDate(false);
			$categories = $this->categoryFactory->create()->getCollection();

			if ($lastFlush !== null && false) {
				$categories->addAttributeToFilter(
					'privatesale_date_end',
					[
						['lt' => $currentTime],
						['gt' => $lastFlush]
					]
				);
			} else {
				$categories->addAttributeToFilter('privatesale_date_end', ['lt' => $currentTime]);
			}

			$this->config->saveConfig(self::LAST_FLUSH_DATE_PATH, $currentTime, 'default', 0);
	        $this->appConfig->reinit();

	        if ($categories->count()) {
	        	foreach ($this->types as $type => $status) {
	        		if ($status) {
		        		$tags = $this->cacheTypeList->cleanType($type);
	        		}
	        	}
	        }
	    }
	}
}
