<?php
/**
 * @category  Apptrian
 * @package   Apptrian_FacebookPixel
 * @author    Apptrian
 * @copyright Copyright (c) 2017 Apptrian (http://www.apptrian.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License
 */

namespace Apptrian\FacebookPixel\Block;

class Code extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;
    
    /**
     * @var \Apptrian\FacebookPixel\Helper\Data
     */
    public $helper;
    
    /**
     * @var \Magento\Framework\Registry
     */
    public $coreRegistry;
    
    /**
     * @var \Magento\Catalog\Helper\Data
     */
    public $catalogHelper;
    
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Apptrian\FacebookPixel\Helper\Data $helper
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Catalog\Helper\Data $catalogHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Apptrian\FacebookPixel\Helper\Data $helper,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Catalog\Helper\Data $catalogHelper,
        array $data = []
    ) {
        $this->storeManager  = $context->getStoreManager();
        $this->helper        = $helper;
        $this->coreRegistry  = $coreRegistry;
        $this->catalogHelper = $catalogHelper;
        parent::__construct($context, $data);
    }
    
    /**
     * Used in .phtml file and returns array of data.
     *
     * @return array
     */
    public function getFacebookPixelData()
    {
        $data = [];
    
        $data['id'] = $this->helper
            ->getConfig('apptrian_facebookpixel/general/pixel_id');
    
        $data['full_action_name'] = $this->getRequest()->getFullActionName();
    
        return $data;
    }
    
    /**
     * Returns product data needed for dynamic ads tracking.
     *
     * @return array
     */
    public function getProductData()
    {
        $p = $this->coreRegistry->registry('current_product');
    
        $data = [];
    
        $data['content_name']     = $this->helper
            ->escapeSingleQuotes($p->getName());
        $data['content_ids']      = $this->helper
            ->escapeSingleQuotes($p->getSku());
        $data['content_type']     = 'product';
        $data['value']            = number_format(
            $this->getCalculatedPrice(),
            2,
            '.',
            ''
        );
        $data['currency']         = $this->getCurrencyCode();
    
        return $data;
    }
    
    /**
     * Returns product calculated price depending option selected in
     * Stores > Cofiguration > Sales > Tax > Price Display Settings
     * If "Excluding Tax" is selected price will not include tax.
     * If "Including Tax" or "Including and Excluding Tax" is selected price
     * will include tax.
     *
     * @return int|float|string
     */
    public function getCalculatedPrice()
    {
        $p = $this->coreRegistry->registry('current_product');
    
        $productType = $p->getTypeId();
    
        $calculatedPrice = 0;
    
        // Tax Display
        // 1 - excluding tax
        // 2 - including tax
        // 3 - including and excluding tax
        $tax = (int) $this->helper->getConfig('tax/display/type');
    
        if ($productType == 'configurable') {
            if ($tax === 1) {
                $calculatedPrice = $p->getFinalPrice();
            } else {
                $calculatedPrice = $this->catalogHelper->getTaxPrice(
                    $p,
                    $p->getFinalPrice(),
                    true,
                    null,
                    null,
                    null,
                    $this->storeManager->getStore()->getId(),
                    true,
                    false
                );
            }
        } elseif ($productType == 'grouped') {
            $associatedProducts = $p->getTypeInstance(true)
                ->getAssociatedProducts($p);
    
            $prices = [];
    
            foreach ($associatedProducts as $associatedProduct) {
                $prices[] = $associatedProduct->getPrice();
            }
    
            if (!empty($prices)) {
                $calculatedPrice = min($prices);
            }
    
        // downloadable, simple, virtual
        } else {
            if ($tax === 1) {
                $calculatedPrice = $p->getFinalPrice();
            } else {
                $calculatedPrice = $this->catalogHelper->getTaxPrice(
                    $p,
                    $p->getFinalPrice(),
                    true,
                    null,
                    null,
                    null,
                    $this->storeManager->getStore()->getId(),
                    false,
                    false
                );
            }
        }
    
        return $calculatedPrice;
    }
    
    /**
     * Returns 3 letter currency code like USD, GBP, EUR, etc.
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return strtoupper(
            $this->storeManager->getStore()->getCurrentCurrency()->getCode()
        );
    }
}
