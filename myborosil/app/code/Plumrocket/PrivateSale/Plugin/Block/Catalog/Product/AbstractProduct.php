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


namespace Plumrocket\PrivateSale\Plugin\Block\Catalog\Product;

class AbstractProduct
{
    /**
     * Module variables
     * @var Array
     */
    protected $vars = [];

    /**
     * Block factory
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    public function __construct(
        \Magento\Framework\View\LayoutFactory $layout,
        \Magento\Framework\Config\View $configView
    ){
        $this->layout = $layout;
        $this->vars = $configView->getVars('Plumrocket_PrivateSale');
    }

    /**
     * Around of product details html
     * @param  Magento\Catalog\Block\Product\ListProduct\Interceptor $provider
     * @param  string $result
     * @param  \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function aroundGetProductDetailsHtml($provider, $result, $product)
    {
        $result = $result($product);
        if ($this->vars['display_on_product_list'] == 'true') {
            $block = $this->layout->create()->createBlock('Plumrocket\PrivateSale\Block\Event\Product')
                ->setProduct($product)
                ->setTemplate('Plumrocket_PrivateSale::event/item.phtml');
            $result .= $block->toHtml();
        }

        return $result;
    }
}
