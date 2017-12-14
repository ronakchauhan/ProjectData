<?php 

namespace Ktpl\ProductView\Plugin\Bundle\Catalog\Product\View\Type;

class Bundle
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
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Json\DecoderInterface $jsonDecoder
     */
    public function __construct(
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Json\DecoderInterface $jsonDecoder
    ) {
        $this->jsonEncoder = $jsonEncoder;
        $this->jsonDecoder = $jsonDecoder;
    }

    /**
     * {@inheritdoc}
     */
    public function aroundGetJsonConfig(
        \Magento\Bundle\Block\Catalog\Product\View\Type\Bundle $subject,
        callable $proceed
    ) {
        $config = $proceed();
        $config = $this->jsonDecoder->decode($config);

        /** @var Option[] $optionsArray */
        $optionsArray = $subject->getOptions();

        foreach ($optionsArray as $optionItem) {
            /* @var $optionItem Option */
            if (!$optionItem->getSelections()) {
                continue;
            }
            $optionId = $optionItem->getId();
            $config['options'][$optionId]['option_label'] = $optionItem->getOptionLabel();
        }

        return $this->jsonEncoder->encode($config);
    }
}
