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

class CategorySaveBefore extends EventObserver
{
    /**
     * Virtual type Plumrocket\PrivateSale\ImageUpload
     * @var \Magento\Catalog\Model\ImageUploader
     */
    protected $imageUploader;

    /**
     * CategorySaveBefore constructor.
     *
     * @param \Plumrocket\PrivateSale\Model\Event          $event
     * @param \Magento\Catalog\Model\CategoryFactory       $categoryFactory
     * @param \Plumrocket\PrivateSale\Model\IndexerFactory $indexerFactory
     * @param \Magento\Framework\App\Request\Http          $request
     * @param \Plumrocket\PrivateSale\Helper\Data          $helper
     * @param \Magento\Catalog\Model\ImageUploader         $imageUploader
     */
    public function __construct(
        \Plumrocket\PrivateSale\Model\Event $event,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Plumrocket\PrivateSale\Model\IndexerFactory $indexerFactory,
        \Magento\Framework\App\Request\Http $request,
        \Plumrocket\PrivateSale\Helper\Data $helper,
        \Magento\Catalog\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($event, $categoryFactory, $indexerFactory, $request, $helper);
        $this->imageUploader = $imageUploader;
    }

    /**
     * Save category products to indexer table
     * @param  \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**
         * Fix for uploader.
         */
        if (class_exists('Magento\Catalog\Model\ImageUploader')) {
            foreach (['privatesale_event_image', 'privatesale_email_image'] as $image) {
                $imageData = $this->request->getParam($image);
                $category = $observer->getEvent()->getCategory();
                if ($imageData && is_array($imageData) && isset($imageData[0]['tmp_name'])) {
                    $category->setData($image, $imageData[0]['name']);
                    $this->imageUploader->moveFileFromTmp($imageData[0]['name']);
                } elseif (empty($imageData)) {
                    $category->setData($image, '');
                }
            }
        }

    }

}
