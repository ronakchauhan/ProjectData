<?php
namespace Ktpl\Infinitescroll\Block;

class Init extends \Magento\Framework\View\Element\Template
{
    private $catalogSession;
    private $registry;

    /**
     * Init constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Session $catalogSession
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->catalogSession = $catalogSession;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @param $fullPath
     * @return mixed
     */
    public function getConfig($fullPath)
    {
        return $this->_scopeConfig->getValue($fullPath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $node
     * @return mixed
     */
    public function getScrollConfig($node)
    {
        return $this->getConfig('ktpl_infinitescroll/' . $node);
    }

    /**
     * @param $selector
     * @return string
     */
    public function getSelector($selector)
    {
        return $this->getScrollConfig('selectors/'.$selector);
    }

    /**
     * @param $design
     * @return string
     */
    public function getDesign($design)
    {
        return $this->getScrollConfig('design/'.$design);
    }

    /**
     * @return mixed
     */
    public function isEnabled()
    {
        $enabled = ($this->getScrollConfig('general/enabled') && $this->isEnabledInCurrentPage());
        return $enabled;
    }

    /**
     * @return mixed
     */
    public function isMemoryActive()
    {
        return $this->getScrollConfig('memory/enabled');
    }

    /**
     * @return mixed
     */
    public function getNextPageNumber()
    {
        return $this->getRequest()->getParam('p');
    }

    /**
     * @return string
     */
    public function getCurrentPageType()
    {
        $where = 'grid';
        $currentCategory = $this->getCurrentCategory();
        if ($currentCategory) {
            $where = "grid";
            if ($currentCategory->getIsAnchor()) {
                $where = "layer";
            }
        }
        $controller = $this->getRequest()->getControllerName();
        if ($controller == "result") {
            $where = "search";
        } elseif ($controller == "advanced") {
            $where = "advanced";
        }
        return $where;
    }

    /**
     * @return mixed
     */
    public function getCurrentCategory()
    {
        return $this->registry->registry('current_category');
    }

    /**
     * check general and instance enable
     * @return bool
     */
    public function isEnabledInCurrentPage()
    {
        $pageType = $this->getCurrentPageType();
        return $this->getScrollConfig('instances/'.$pageType);
    }

    /**
     * @return bool|false
     */
    public function getLoaderImage()
    {
        $url = $this->getScrollConfig('design/loading_img');
        
        if (!empty($url)) {

            $mediaUrl = $this->_storeManager
                         ->getStore()
                         ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

            $url = strpos($url, 'http') === 0 ? $url : $mediaUrl ."category/" . $url;
        }

        return empty($url) ? false : $url;
    }

    /**
     * @return string
     */
    public function getProductListMode()
    {
        // user mode
        $paramProductListMode = $this->getRequest()->getParam('product_list_mode');
        $currentMode = $paramProductListMode ? $paramProductListMode : $this->catalogSession->getDisplayMode();
        if ($currentMode) {
            switch ($currentMode) {
                case 'list':
                    $productListMode = 'list';
                    break;
                case 'grid':
                default:
                    $productListMode = 'grid';
            }
        } else {
            $defaultMode = $this->getConfig('catalog/frontend/list_mode');
            switch ($defaultMode) {
                case 'grid-list':
                    $productListMode = 'grid';
                    break;
                case 'list-grid':
                    $productListMode = 'list';
                    break;
                default:
                    $productListMode = $defaultMode;
            }
        }
        return $productListMode;
    }
}
