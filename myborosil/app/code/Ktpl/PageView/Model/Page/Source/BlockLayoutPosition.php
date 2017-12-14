<?php

namespace Ktpl\PageView\Model\Page\Source;

use Magento\Framework\Data\OptionSourceInterface;

class BlockLayoutPosition implements OptionSourceInterface
{
    const POSITION_BEFORE_CONTENT = 0;
    const POSITION_AFTER_CONTENT = 1;

    /**
     * @var array
     */
    protected $options;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $this->options = [
            [
                'label' => __('Before Content'),
                'value' => self::POSITION_BEFORE_CONTENT
            ],
            [
                'label' => __('After Content'),
                'value' => self::POSITION_AFTER_CONTENT
            ]
        ];
        
        return $this->options;
    }
}
