<?php 

namespace Ktpl\ProductView\Plugin\ConfigurableProduct\Catalog\Product\View\Type;

class Configurable
{
    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var \Magento\Framework\Json\DecoderInterface
     */
    protected $jsonDecoder;

    /**
	 * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute
	 */
	protected $_eavAttribute;

    /**
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Json\DecoderInterface $jsonDecoder
     */
    public function __construct(
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Json\DecoderInterface $jsonDecoder,
        \Magento\Eav\Model\Entity\Attribute $eavAttribute
    ) {
        $this->jsonEncoder = $jsonEncoder;
        $this->jsonDecoder = $jsonDecoder;
        $this->_eavAttribute = $eavAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function aroundGetJsonConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        callable $proceed
    ) {
        $config = $proceed();
        $config = $this->jsonDecoder->decode($config);

        foreach($config['attributes'] as $key => $data)
        {
        	$attributeObject = $this->_eavAttribute->load($key);
        	
        	$swatchToDropdown = $attributeObject->getSwatchToDropdown();
        	
        	if(isset($swatchToDropdown) && $swatchToDropdown == 1)
        		$data['swatch_to_dropdown'] = 1;
        	else
        		$data['swatch_to_dropdown'] = 0;

        	$isUserGuide = $attributeObject->getIsUserGuide();
        	
        	if(isset($isUserGuide) && $isUserGuide == 1)
        		$data['is_user_guide'] = 1;
        	else
        		$data['is_user_guide'] = 0;

        	$config['attributes'][$key] = $data;
        }

        $sizeGuide = $subject->getProduct()->getSizeGuide();

        if(isset($sizeGuide) && trim($sizeGuide) != '')
            $config['size_guide'] = $sizeGuide;

        return $this->jsonEncoder->encode($config);
    }
}
