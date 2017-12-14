<?php

namespace Ktpl\CategoryView\Model\Catalog\Category\Attribute\Source\Listing;

/**
 * Catalog product landing page attribute source
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Section extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const VALUE_DEFAULT_LAYOUT = '';

    /**
     * @var \Ktpl\SectionView\Model\SectionFactory $sectionFactory
     */
    protected $_sectionFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Ktpl\SectionView\Model\SectionFactory $sectionFactory
    ) {
        $this->_sectionFactory = $sectionFactory;
    }


    /**
     * @return array
     */
    public function getAllOptions($excludeDefault = false)
    {
        $sectionObject = $this->_sectionFactory->create();
       
       if (!$this->_options) { 
        
            $this->_options = [['value' => '0', 'label' => __("Select Section")]];

            foreach($sectionObject->getCollection() as $section)
            {
                $this->_options[] = ['value' => $section->getId(), 'label' => $section->getTitle()];
            }
        }
        
        return $this->_options;
    }
}
