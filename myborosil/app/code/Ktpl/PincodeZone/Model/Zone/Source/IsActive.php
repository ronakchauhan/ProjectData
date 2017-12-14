<?php

namespace Ktpl\PincodeZone\Model\Zone\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \Ktpl\PincodeZone\Model\Zone
     */
    protected $zoneModel;

    /**
     * Constructor
     *
     * @param \Ktpl\PincodeZone\Model\Zone $zoneModel
     */
    public function __construct(\Ktpl\PincodeZone\Model\Zone $zoneModel)
    {
        $this->zoneModel = $zoneModel;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->zoneModel->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
