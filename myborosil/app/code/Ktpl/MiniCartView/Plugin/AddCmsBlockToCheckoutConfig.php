<?php

namespace Ktpl\MiniCartView\Plugin;

use Magento\Framework\View\LayoutInterface;

class AddCmsBlockToCheckoutConfig
{
    /** @var LayoutInterface  */
    protected $_layout;

    public function __construct(LayoutInterface $layout)
    {
        $this->_layout = $layout;
    }

    public function afterGetConfig(
        \Magento\Checkout\Block\Cart\Sidebar $subject,
        $config
        )
    {
        $cmsBlockId = 'minicart_msg';

        $config['cmsBlockMessage'] = $this->_layout->createBlock('Magento\Cms\Block\Block')->setBlockId($cmsBlockId)->toHtml();

        return $config;
    }
}
