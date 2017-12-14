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

class ConfigDataSaveBefore extends EventObserver
{

    /**
     * {@inheritdoc}
     */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        $data = $this->request->getParams();
        $data = $data['groups']['settings']['fields'];

        if ($data['before_start_action']['value'] != $this->helper->getConfig(\Plumrocket\PrivateSale\Model\Config\Source\Beforeeventstart::CONFIG_PATH)
            || $data['end_action']['value'] != $this->helper->getConfig(\Plumrocket\PrivateSale\Model\Attribute\Source\Category\Eventend::CONFIG_PATH)
        ) {
            $indexes = $this->indexerFactory->create()->getCollection()
                ->addFieldToFilter(
                    ['event_start_action_use_config', 'event_end_action_use_config'],
                    [1, 1]
                );

            foreach ($indexes as $index) {
                try
                {
                    if ($index->getData('event_start_action_use_config')) {
                        $index->setEventStartAction($data['before_start_action']['value']);
                    }

                    if ($index->getData('event_end_action_use_config')) {
                        $index->setEventEndAction($data['end_action']['value']);
                    }

                    $index->save();
                } catch (\Exception $e) {

                }
            }
        }
	}
}
