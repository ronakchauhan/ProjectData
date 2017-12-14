<?php

namespace Ktpl\Blog\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Data\Tree\Node;
use Magento\Store\Model\ScopeInterface;
use Ktpl\Blog\Helper\Config;

/**
 * Blog observer
 */
class PageBlockHtmlTopmenuBethtmlBeforeObserver implements ObserverInterface
{
    /**
     * Show top menu item config path
     */
    const XML_PATH_TOP_MENU_SHOW_ITEM = 'mfblog/top_menu/show_item';

    /**
     * Top menu item text config path
     */
    const XML_PATH_TOP_MENU_ITEM_TEXT = 'mfblog/top_menu/item_text';

    /**
     * @var \Ktpl\Blog\Model\Url
     */
    protected $_url;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param \Ktpl\Blog\Model\Url $url
     */
    public function __construct(
        \Ktpl\Blog\Model\Url $url,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_url = $url;
    }

    /**
     * Page block html topmenu gethtml before
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_scopeConfig->isSetFlag(static::XML_PATH_TOP_MENU_SHOW_ITEM, ScopeInterface::SCOPE_STORE)) {
            return;
        }

        if (!$this->_scopeConfig->isSetFlag(Config::XML_PATH_EXTENSION_ENABLED, ScopeInterface::SCOPE_STORE)) {
            return;
        }

        /** @var \Magento\Framework\Data\Tree\Node $menu */
        $menu = $observer->getMenu();
        $block = $observer->getBlock();

        $tree = $menu->getTree();
        $data = [
            'name'      => $this->_scopeConfig->getValue(static::XML_PATH_TOP_MENU_ITEM_TEXT, ScopeInterface::SCOPE_STORE),
            'id'        => 'ktpl-blog',
            'url'       => $this->_url->getBaseUrl(),
            'is_active' => ($block->getRequest()->getModuleName() == 'blog'),
        ];
        $node = new Node($data, 'id', $tree, $menu);
        $menu->addChild($node);
    }
}
