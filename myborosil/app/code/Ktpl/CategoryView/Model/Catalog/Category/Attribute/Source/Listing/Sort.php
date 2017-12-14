<?php

namespace Ktpl\CategoryView\Model\Catalog\Category\Attribute\Source\Listing;

/**
 * Catalog product landing page attribute source
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Sort extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    protected $_config;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Config $_config
    ) {
        $this->_config = $_config;
    }


    /**
     * @return array
     */
    public function getAllOptions($excludeDefault = false)
    {
        $attr = $this->_config->getAttributesUsedForSortBy();

        if(!$this->_options) { 
        
            $this->_options = [['value' => '', 'label' => __("Select Attribute")]];

            foreach($attr as $attributes)
            {                
                if($attributes['entity_type_id'] == 4 && $attributes['frontend_input'] == 'select')
                {
                    $this->_options[] = ['value' => $attributes['attribute_code'], 'label' => $attributes['frontend_label']];
                }
            }
        }
        return $this->_options;
    }
}
