<?php

namespace Ktpl\PincodeSearch\Block\Adminhtml\Pincode;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $_collectionFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Ktpl\PincodeSearch\Model\ResourceModel\Pincode\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('pincodeGrid');
        $this->_exportPageSize = 10000;
    }

    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'pincode',
            ['header' => __('Pincode'), 'index' => 'pincode', 'default' => '*']
        );

        $this->addColumn(
            'is_cod_available',
            ['header' => __('Is COD Available?'), 'index' => 'is_cod_available', 'default' => '*']
        );

        $this->addColumn(
            'delivery_time',
            ['header' => __('Delivery Time'), 'index' => 'delivery_time', 'default' => '*']
        );

        $this->addColumn('delivery_message', ['header' => __('Delivery Message'), 'index' => 'delivery_message']);

        $this->addColumn('is_active', ['header' => __('Is Pincode Active?'), 'index' => 'is_active']);

        return parent::_prepareColumns();
    }
}
