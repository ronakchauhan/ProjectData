<?php

namespace Ktpl\SectionView\Ui\Component\Listing\Column\Section;

use Magento\Framework\Option\ArrayInterface;

class ImagePositionOptions implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            0 => [
                'label' => 'Please select',
                'value' => 0
            ],
            1 => [
                'label' => 'Top banner',
                'value' => 1
            ],
            2  => [
                'label' => 'In Between List',
                'value' => 2
            ],            
        ];

        return $options;
    }
}
