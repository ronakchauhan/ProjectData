<?php

namespace Ktpl\CategoryView\Helper;

use Magento\Catalog\Model\ProductFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper 
{
    /**
     * @var Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;
    
    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    protected $imageBuilder; 

    protected $imageHelper;

    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Catalog product
     *
     * @var \Magento\Catalog\Helper\Product
     */
    protected $catalogProduct;

    protected $registry;
 
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        ProductFactory $productFactory,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \Magento\Catalog\Helper\Product $catalogProduct,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->_productFactory = $productFactory;
        $this->imageBuilder = $imageBuilder;
        $this->catalogProduct = $catalogProduct;
        $this->imageHelper = $imageHelper;
        $this->_objectManager = $objectmanager;
        $this->registry = $registry;
    }
 
    /**
     * Retrieve product image
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }

    public function getGalleryImages(\Magento\Catalog\Model\Product $product)
    {
        $images = $product->getMediaGalleryImages();
        if ($images instanceof \Magento\Framework\Data\Collection) {
            foreach ($images as $image) {
                /** @var $image \Magento\Catalog\Model\Product\Image */
                $image->setData(
                    'small_image_url',
                    $this->imageHelper->init($product, 'product_page_image_small')
                        ->setImageFile($image->getFile())
                        ->getUrl()
                );
                $image->setData(
                    'medium_image_url',
                    $this->imageHelper->init($product, 'product_page_image_medium')
                        ->constrainOnly(true)->keepAspectRatio(true)->keepFrame(false)
                        ->setImageFile($image->getFile())
                        ->getUrl()
                );
                $image->setData(
                    'large_image_url',
                    $this->imageHelper->init($product, 'product_page_image_large')
                        ->constrainOnly(true)->keepAspectRatio(true)->keepFrame(false)
                        ->setImageFile($image->getFile())
                        ->getUrl()
                );
                // echo"<pre/>"; print_r($image->getData());exit;
            }
        }

        // echo"<pre/>"; print_r($images->getData());exit;
        return $images;
    }

    public function getCurrentProduct($productId)
    {
        $productCollection = $this->_productFactory->create();
        return $productCollection->load($productId);
    }

    /**
     * Retrieve Product
     * @param \Magento\Catalog\Model\Product $product
     * @return int[]
     */
    public function getBundleProductoptions($product)
    {
        $productId = $product->getId();//any product id
        $_product = $this->_productFactory->create()->load($productId);

        $productTypeInstance = $this->_objectManager->get('Magento\ConfigurableProduct\Model\Product\Type\Configurable');
         
        $productAttributeOptions = $productTypeInstance->getConfigurableAttributesAsArray($product);

        echo"<pre/>"; print_r($productAttributeOptions);exit;
        $optionCollection = $typeInstance->getOptionsCollection($_product);

        $selectionCollection = $typeInstance->getSelectionsCollection(
            $typeInstance->getOptionsIds($_product),
            $_product
        );
        $this->options = $optionCollection->appendSelections(
            $selectionCollection,
            false,
            $this->catalogProduct->getSkipSaleableCheck()
        );
        
        return $this->options;
    }

    /**
     * Retrieve Product
     * @param \Magento\Catalog\Model\Product $product
     * @return int[]
     */
    public function getGroupedProducts(\Magento\Catalog\Model\Product $product)
    {
        $productId = $product->getId();//any product id
        $_product = $this->_productFactory->create()->load($productId);

        $typeInstance = $_product->getTypeInstance();
        
        $pids = $typeInstance->getChildrenIds($product->getId());
        
        return $pids;
    }

    public function getCurrentCategory()
    {
        return $this->registry->registry('current_category');
    }

    public function setCategorySortAttribute()
    {
        $category = $this->getCurrentCategory();

        $this->registry->register('default_sort', $category->getDefaultSortBy()); ;
    }

    public function getAttributeLabel(\Magento\Catalog\Model\Product $product, $attributeId)
    {
        $_product = $this->_objectManager->get('Magento\Catalog\Model\Product')->load($product->getId());

        $attr = $_product->getResource()->getAttribute($attributeId);
        
        if ($attr->usesSource()) {
           return $attr->getSource()->getOptionText($product->getData($attributeId));
        }

         return false;
    }
}