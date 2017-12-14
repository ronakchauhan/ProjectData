<?php

namespace Ktpl\Testimonial\Block;

class Testimonial extends \Magento\Framework\View\Element\Template
{
	/**    
     * @var \Ktpl\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory
     */
    protected $_itemCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

	/**
     *  Testimonial Constructor
     * @param \Magento\Framework\View\Element\Template\Context                    $context               context
     * @param \Ktpl\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory $itemCollectionFactory Testimonial collection factory
     * @param array                                                               $data                  data array
     */
    public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
        \Ktpl\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory $itemCollectionFactory,
        array $data = []
    ) {
        $this->_itemCollectionFactory = $itemCollectionFactory;
        $this->_storeManager = $context->getStoreManager();
        parent::__construct($context, $data);
    }

    /**
     * Get Testimonial COllection
     * @return \Ktpl\Testimonial\Model\ResourceModel\Testimonial
     */
    public function getItemCollection()
    {
        $collection = $this->_itemCollectionFactory->create()
                  ->addFieldToFilter('status', ['eq' => '1']);
        
        $collection->getSelect()
                ->join( array('ktpl_store'=>'ktpl_testimonials_store'), 'main_table.entity_id = ktpl_store.entity_id', array('ktpl_store.store_id'));
        
        $collection->addFilterToMap('store_id', 'ktpl_store.store_id');
        
        $collection->addFieldToFilter('store_id', ['eq' => $this->getStoreId()]);
        
        return $collection;
	}

    /**
     * Get store identifier
     *
     * @return  int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
}
