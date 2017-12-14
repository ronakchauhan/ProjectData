<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ktpl\ImageOptions\Plugin\Observer;

use Magento\Framework\Event\ObserverInterface;

class ChangeTemplateObserver
{
    /**
     * @param mixed $observer
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return void
     */
    public function aroundExecute(\Magento\ProductVideo\Observer\ChangeTemplateObserver $subject, callable $proceed, \Magento\Framework\Event\Observer $observer)
    {
        $observer->getBlock()->setTemplate('Ktpl_ImageOptions::helper/gallery.phtml');
    }
}
