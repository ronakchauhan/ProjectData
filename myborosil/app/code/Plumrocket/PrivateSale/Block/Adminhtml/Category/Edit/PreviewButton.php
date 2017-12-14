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

namespace Plumrocket\PrivateSale\Block\Adminhtml\Category\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Catalog\Block\Adminhtml\Category\AbstractCategory;

/**
 * Class DeleteButton
 */
class PreviewButton extends AbstractCategory implements ButtonProviderInterface
{
    /**
     * Delete button
     *
     * @return array
     */
    public function getButtonData()
    {
        $category = $this->getCategory();
        $categoryId = (int)$category->getId();

        if ($categoryId && !in_array($categoryId, $this->getRootIds())) {
            return [
                'id' => 'privatesale_preview',
                'label' => __('Preview'),
                'on_click' => "window.open('" . $this->getPreviewUrl($categoryId) . "')",
                'class' => 'privatesale-preview',
                'sort_order' => 10
            ];
        }

        return [];
    }

    /**
     * @param array $args
     * @return string
     */
    public function getPreviewUrl($categoryId)
    {
        return $this->getUrl('prprivatesale/preview/category', ['id' => $categoryId]);
    }
}
