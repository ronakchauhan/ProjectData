<?php

namespace Ktpl\ImageOptions\Plugin\Product\View;

use Ktpl\ProductView\Model\Catalog\Product\Attribute\Source\View\Layout;

class Gallery
{
	/**
     * @var \Magento\Framework\Registry
     */
	protected $_registry;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Stdlib\ArrayUtils $arrayUtils
     * @param EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $registry
    ) {
        $this->_registry = $registry;
    }


    public function aroundGetGalleryImagesJson(
        \Magento\Catalog\Block\Product\View\Gallery $subject,
        callable $proceed) 
    {

    	if ($this->getCurrentLayout() == Layout::VALUE_LAYOUT_1 || $this->getCurrentLayout() == Layout::VALUE_LAYOUT_2)
    	{
    		return $proceed();
    	}
    	else
    	{
	    	$imagesItems = [];
	    	foreach ($subject->getGalleryImages() as $image)
	    	{
	    		if (!$image->getPartsimage() && !$image->getIsbigimage()) 
	            {
	                $imagesItems[] = [
	                    'thumb' => $image->getData('small_image_url'),
	                    'img' => $image->getData('medium_image_url'),
	                    'full' => $image->getData('large_image_url'),
	                    'caption' => $image->getLabel(),
	                    'position' => $image->getPosition(),
	                    'isMain' => $subject->isMainImage($image),
	                ];
	            }
	    	}

	    	if (empty($imagesItems)) {
	            $imagesItems[] = [
	                'thumb' => $this->_imageHelper->getDefaultPlaceholderUrl('thumbnail'),
	                'img' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
	                'full' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
	                'caption' => '',
	                'position' => '0',
	                'isMain' => true,
	            ];
	        }
	        return json_encode($imagesItems);
    	}
    }

    public function getCurrentLayout()
    {
 		$product =  $this->_registry->registry('current_product');
 		return $product->getViewLayout();
    }
}