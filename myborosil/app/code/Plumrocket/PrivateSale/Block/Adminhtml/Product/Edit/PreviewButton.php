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

namespace Plumrocket\PrivateSale\Block\Adminhtml\Product\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Catalog\Block\Adminhtml\Product\Edit as ProductEdit;


class PreviewButton extends ProductEdit implements ButtonProviderInterface
{

    /**
     * Retrieve button data
     * @return Array
     */
    public function getButtonData()
    {
        $product = $this->getProduct();
        $productId = (int)$product->getId();

        if ($productId) {
            return [
                'id' => 'privatesale_preview',
                'label' => __('Preview'),
                'on_click' => "window.open('" . $this->getPreviewUrl($productId) . "')",
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
    public function getPreviewUrl($id)
    {
        return $this->getUrl('prprivatesale/preview/product', ['id' => $id]);
    }
}
