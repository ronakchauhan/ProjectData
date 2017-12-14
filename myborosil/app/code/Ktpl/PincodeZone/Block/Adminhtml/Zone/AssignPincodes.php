<?php

namespace Ktpl\PincodeZone\Block\Adminhtml\Zone;

class AssignPincodes extends \Magento\Backend\Block\Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'zone/assign_pincodes.phtml';

    /**
     * @var \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
     */
    protected $blockGrid;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    protected $_zoneFactory;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * AssignProducts constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Ktpl\PincodeZone\Model\ZoneFactory $zoneFactory,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->_zoneFactory = $zoneFactory;
        $this->jsonEncoder = $jsonEncoder;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve instance of grid block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                'Ktpl\PincodeZone\Block\Adminhtml\Zone\Tab\Pincode',
                'zone.pincode.grid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * Return HTML of grid block
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }

    public function getPincodesJson()
    {
        $zone    = $this->getZone();
        $pincodes = $this->getZone()->getStorePincodes($zone);
        if (!empty($pincodes)) {
            return $this->jsonEncoder->encode($pincodes);
        }
        return '{}';
    }

    public function getZone()
    {   
        $zoneId = $this->getRequest()->getParam('entity_id');
        $zone   = $this->_zoneFactory->create();
        if ($zoneId) {
            $zone->load($zoneId);
        }

        return $zone;
    }
}
