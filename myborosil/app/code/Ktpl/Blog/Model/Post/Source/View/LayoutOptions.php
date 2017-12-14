<?php 

namespace Ktpl\Blog\Model\Post\Source\View;

use Magento\Framework\Option\ArrayInterface;

class LayoutOptions implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            0 => [
                'label' => 'Default Layout',
                'value' => 0
            ],
            1 => [
                'label' => 'Blog Post Layout 1',
                'value' => 1
            ],
            2  => [
                'label' => 'Blog Post Layout 2',
                'value' => 2
            ],
            3  => [
                'label' => 'Blog Post Layout 3',
                'value' => 3
            ],
        ];

        return $options;
    }
}