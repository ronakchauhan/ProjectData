<?php

namespace Ktpl\SectionView\Model\Section\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \Ktpl\SectionView\Model\Section
     */
    protected $sectionModel;

    /**
     * Constructor
     *
     * @param \Ktpl\SectionView\Model\Section $sectionModel
     */
    public function __construct(\Ktpl\SectionView\Model\Section $sectionModel)
    {
        $this->sectionModel = $sectionModel;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->sectionModel->getAvailableStatuses();
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
