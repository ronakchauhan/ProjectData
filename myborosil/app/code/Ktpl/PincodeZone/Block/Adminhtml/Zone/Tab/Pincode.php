<?php
/**
 * Pincode in zone grid
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Ktpl\PincodeZone\Block\Adminhtml\Zone\Tab;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;

class Pincode extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_pincodeFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Catalog\Model\ProductFactory $pincodeFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Ktpl\PincodeSearch\Model\PincodeFactory $pincodeFactory,
        \Ktpl\PincodeZone\Model\ZoneFactory $zoneFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_pincodeFactory = $pincodeFactory;
        $this->_zoneFactory = $zoneFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('zone_pincodes');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    /**
     * @return array|null
     */
    public function getZone()
    {
        return $this->_coreRegistry->registry('ktpl_pincode_zone');
    }

    /**
     * @param Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in category flag
        if ($column->getId() == 'in_zone') {
            $productIds = $this->_getSelectedPincodes();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * @return Grid
     */
    protected function _prepareCollection()
    {
        if ($this->_getZone()->getId()) {
            $this->setDefaultFilter(['in_zone' => 1]);
        }
        $collection = $this->_pincodeFactory->create()->getCollection()->addFieldToSelect('*');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_zone',
            [
                'type' => 'checkbox',
                'name' => 'in_zone',
                'values' => $this->_getSelectedPincodes(),
                'index' => 'entity_id',
                'header_css_class' => 'col-select col-massaction',
                'column_css_class' => 'col-select col-massaction'
            ]
        );

        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn('pincode', ['header' => __('Pincode'), 'index' => 'pincode']);
        $this->addColumn('is_cod_available', ['header' => __('Is COD Available'), 'index' => 'is_cod_available']);
        $this->addColumn('delivery_time', ['header' => __('Delivery Time'), 'index' => 'delivery_time']);
        $this->addColumn('delivery_message', ['header' => __('Delivery Message'), 'index' => 'delivery_message']);
        $this->addColumn('is_active', ['header' => __('Is Active'), 'index' => 'is_active']);

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/pincodesgrid', ['_current' => true]);
    }

    /**
     * @return array
     */
    protected function _getSelectedPincodes()
    {
        $zone    = $this->_getZone();
        $pincodes =  $zone->getPincodes($zone);

        return $pincodes;
    }
    protected function _getZone()
    {
        $zoneId = $this->getRequest()->getParam('entity_id');
        $zone   = $this->_zoneFactory->create();
        if ($zoneId) {
            $zone->load($zoneId);
        }
        return $zone;
    }
}
