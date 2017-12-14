<?php
/**
 * Catalog super product configurable part block
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\ProductView\Block\Product\View\Type;
  
/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Configurable
{
    protected $jsonEncoder;
    protected $jsonDecoder;
    protected $swatchHelper;

    public function __construct(
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Json\DecoderInterface $jsonDecoder,
        \Ktpl\ProductView\Helper\ConfigureData $swatchHelper
    ) {

        $this->jsonDecoder = $jsonDecoder;
        $this->jsonEncoder = $jsonEncoder;
        $this->swatchHelper = $swatchHelper;
    }

    /**
     * Composes configuration for js
     *
     * @return string
     */
    public function aroundGetJsonConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        \Closure $proceed
    )
    {
        $currentProduct = $subject->getProduct();
        $description = $this->swatchHelper->getOptionsDescription($currentProduct, $subject->getAllowProducts());
        $config = $proceed();
        $config = $this->jsonDecoder->decode($config);
        $config['description'] = isset($description['description']) ? $description['description'] : [];
        return $this->jsonEncoder->encode($config);
    }

   
}
