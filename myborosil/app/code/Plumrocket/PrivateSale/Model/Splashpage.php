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


namespace Plumrocket\PrivateSale\Model;

class Splashpage extends \Magento\Framework\Model\AbstractModel
{

    const CONFIG_PATH = 'prprivatesale/splashpage/data';

    /**
     * Data
     * @var array
     */
    protected $_sData;

    /**
     * Helper
     * @var \Plumrocket\PrivateSale\Helper\Data
     */
	protected $helper;

    /**
     * Json Helper
     * @var \Magento\Framework\Json\Helper\Data
     */
	protected $jsonHelper;

    /**
     * Image factory
     * @var \Plumrocket\PrivateSale\Model\ImageFactory
     */
	protected $imageFactory;

    /**
     * Event
     * @var Event
     */
	protected $event;

    /**
     * Customer session
     * @var \Magento\Customer\Model\Session
     */
	protected $customerSession;

    /**
     * Request
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * Constructor
     * @param \Magento\Framework\Model\Context           $context
     * @param \Magento\Framework\Registry                $registry
     * @param \Plumrocket\PrivateSale\Helper\Data        $helper
     * @param \Magento\Framework\App\Request\Http        $request
     * @param \Plumrocket\PrivateSale\Model\ImageFactory $imageFactory
     * @param Event                                      $event
     * @param \Magento\Customer\Model\Session            $customerSession
     * @param \Magento\Framework\Json\Helper\Data        $jsonHelper
     * @param array                                      $data
     */
	public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
 		\Plumrocket\PrivateSale\Helper\Data $helper,
        \Magento\Framework\App\Request\Http $request,
 		\Plumrocket\PrivateSale\Model\ImageFactory $imageFactory,
 		Event $event,
 		\Magento\Customer\Model\Session $customerSession,
 		\Magento\Framework\Json\Helper\Data $jsonHelper,
        array $data = []
    ) {
		$this->helper = $helper;
		$this->imageFactory = $imageFactory;
		$this->jsonHelper = $jsonHelper;
		$this->event = $event;
        $this->request = $request;
		$this->customerSession = $customerSession;
        parent::__construct($context, $registry, null, null, $data);
    }

	/**
	 * {@inheritdoc}
	 */
    public function getData($key = '', $index = null)
	{
		if (empty($this->_sData)) {

            $storeId = null;
            $scope = null;

            if (isset($this->_data['admin'])) {
                if (isset($this->_data['store_id'])) {
                    $storeId = $this->_data['store_id'];
                    $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                } else {
                    $scope = \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT;
                }
            }

			$_data = $this->helper->getConfig(self::CONFIG_PATH, $storeId, $scope);
			$data = ($_data == null) ? [] : $this->jsonHelper->jsonDecode($_data);

     		$this->setData($data);
            $this->_sData = $data;
		}

		return parent::getData($key, $index);
	}

    /**
     * Is enabled redirect to login page
     * @return boolean
     */
    public function isEnabledRedirect()
    {
        return $this->getData('enabled_page') != \Plumrocket\PrivateSale\Model\Config\Source\Splashpagestatus::STATUS_DISABLED;
    }

    /**
     * Does must be shown default image
     * @return boolean
     */
    public function showDefaultImage()
    {
        $count = $this->imageFactory->create()
            ->getCollection()
            ->getSize();

        return $count == 0;
    }

    /**
     * Retrieve images
     * @return string
     */
    public function getImages()
    {
        $time = $this->event->getCurrentDate();

        $colls = $this->imageFactory->create()
            ->getCollection()
            ->addFieldToFilter('exclude', 0)
            ->setOrder('sort_order', 'ASC');

        $images = [];

        $first = null;
        $active = null;
        foreach ($colls as $coll) {
            $timeStart = $this->event->toTime($coll->getActiveFrom());
            $timeEnd = $this->event->toTime($coll->getActiveTo());


            $coll->setActiveFrom($timeStart);
            $coll->setActiveTo($timeEnd);
            $coll->setTime($time);

            if (
                ($timeStart == 0 || $timeStart < $time)
                && ($timeEnd == 0 || $timeEnd > $time))
            {
                $images[] = $coll->getData();
            }
        }

        return $images;
    }
}
