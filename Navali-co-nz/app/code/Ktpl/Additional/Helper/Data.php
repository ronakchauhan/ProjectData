<?php

namespace Ktpl\Additional\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var ProductFactory
     */
    protected $_productCollectionFactory;

    protected $_productloader;
    
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
    
    protected $_scopeConfig;
    
    protected $_categoryFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $_productCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        // \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\ObjectManagerInterface $objectmanager
    ) {
        $this->_productCollectionFactory = $_productCollectionFactory;
        $this->_productloader = $_productloader;
        $this->_categoryFactory = $categoryFactory;
        $this->_objectManager = $objectmanager;
        $this->_scopeConfig    = $context->getScopeConfig();
        parent::__construct($context);
    }

    public function getCurrentProductSwatchImage($optionIdvalue)
    {
        $swatchHelper=$this->_objectManager->get("Magento\Swatches\Helper\Media");
        $swatchCollection = $this->_objectManager->create('Magento\Swatches\Model\ResourceModel\Swatch\Collection');
       
        $swatchCollection->addFieldtoFilter('option_id',$optionIdvalue);
        
        $item=$swatchCollection->getFirstItem();
         
         return $swatchHelper->getSwatchAttributeImage('swatch_thumb', $item->getValue());
    }

    public function getGroupProductsSwatch($product)
    {
        $products = $this->_productCollectionFactory->create();
        $products->addAttributeToSelect('color');
        $products->addAttributeToFilter('group_id', ['eq' => $product->getGroupId()]);
        $products->addAttributeToFilter('entity_id', ['neq' => $product->getId()]);

        $pIds = array();
        foreach ($products as $key => $product) {
            array_push($pIds, $product->getId());
        }

        return $pIds;
    }

    public function getProductLoad($productId)
    {
        return $this->_productloader->create()->load($productId);
        
        // echo"<pre/>"; print_r($product->debug());exit;
    }

}