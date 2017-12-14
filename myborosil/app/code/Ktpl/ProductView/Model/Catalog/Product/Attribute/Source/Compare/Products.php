<?php

namespace Ktpl\ProductView\Model\Catalog\Product\Attribute\Source\Compare;

/**
 * Catalog product landing page attribute source
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Products extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $collectionFactory;

     /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['value' => '0', 'label' => __('[ NONE ]')]
            ];

            $collection = $this->collectionFactory->create();
            $collection->addAttributeToSelect('name');
            foreach($collection as $product)
                $this->_options[] = ['value' => $product->getId(), 'label' => $product->getName()];
        }
        return $this->_options;
    }
}
