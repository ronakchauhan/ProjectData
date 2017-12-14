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

namespace Plumrocket\PrivateSale\Controller\Ajax;

/**
 * Time action
 */
class TimeAction extends \Magento\Framework\App\Action\Action
{
    /**
     * Event
     * @var \Plumrocket\PrivateSale\Model\Event
     */
    protected $event;

    /**
     * Json Helper
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $helperJson;
    
    /**
     * TimeAction constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Plumrocket\PrivateSale\Model\Event   $event
     * @param \Magento\Framework\Json\Helper\Data   $helperJson
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Plumrocket\PrivateSale\Model\Event $event,
        \Magento\Framework\Json\Helper\Data $helperJson
    ) {
        $this->event = $event;
        $this->helperJson = $helperJson;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $data = $this->getRequest()->getParam('data');

            $currentTimestamp = $this->event->getCurrentDate();

            foreach ($data as $itemId => $time) {
                if ($time > $currentTimestamp) {
                    $data[$itemId] = $time - $currentTimestamp;
                } else {
                    $data[$itemId] = 0;
                }
            }

            return $this->getResponse()->setBody
            (
                $this->helperJson->jsonEncode(
                    [
                        'success' => true,
                        'data' => $data
                    ]
                )
            );
        }
    }
}
