<?php

namespace Ktpl\PincodeSearch\Model\Pincode\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \Ktpl\PincodeSearch\Model\Pincode
     */
    protected $pincodeModel;

    /**
     * Constructor
     *
     * @param \Ktpl\PincodeSearch\Model\Pincode $pincodeModel
     */
    public function __construct(\Ktpl\PincodeSearch\Model\Pincode $pincodeModel)
    {
        $this->pincodeModel = $pincodeModel;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->pincodeModel->getAvailableStatuses();
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
