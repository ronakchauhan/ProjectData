<?php 

namespace Ktpl\Blog\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class VisibilityOptions implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            0 => [
                'label' => 'Visible Everywhere',
                'value' => 0
            ],
            1 => [
                'label' => 'Only in Selected Categories',
                'value' => 1
            ],
            2  => [
                'label' => 'Hide in Selected Categories',
                'value' => 2
            ],
        ];

        return $options;
    }
}