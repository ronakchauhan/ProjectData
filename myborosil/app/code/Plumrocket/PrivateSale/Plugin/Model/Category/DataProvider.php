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
 * @package     Plumrocket_SocialLoginPro
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\PrivateSale\Plugin\Model\Category;

class DataProvider
{
    /**
     * Store manager
     * @var \Magento\Store\Model\StoreManagerInterface
     */
	protected $storeManager;

    /**
     * Constructor
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
	public function __construct(
		\Magento\Store\Model\StoreManagerInterface $storeManager
	) {
		$this->storeManager = $storeManager;
	}

    /**
     * After get data
     */
	public function afterGetData(\Magento\Catalog\Model\Category\DataProvider $subject, $result)
    {
        $category = $subject->getCurrentCategory();

        if ($category) {
        	$categoryData = $result[$category->getId()];

            foreach (['privatesale_email_image', 'privatesale_event_image'] as $image) {
                if (isset($categoryData[$image])) {
                    unset($categoryData[$image]);
                    $categoryData[$image][0]['name'] = $category->getData($image);
                    $categoryData[$image][0]['url'] = $this->getImageUrl($category->getData($image));
                }
            }

            $result[$category->getId()] = $categoryData;
        }
        return $result;
    }

    /**
     * Retrieve image url
     * @param  string $image
     * @return string
     */
    protected function getImageUrl($image)
    {
        if (!is_array($image)) {
        	return $this->storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . 'catalog/category/' . $image;
        }
    }
}