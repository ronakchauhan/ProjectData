<?php

namespace Ktpl\Blog\Model\Post\Source\View;

/**
 * Catalog product landing page attribute source
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Layout extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const VALUE_DEFAULT_LAYOUT = 'default_layout';
    const VALUE_LAYOUT_1 = 'layout_1';
    const VALUE_LAYOUT_2 = 'layout_2';
    const VALUE_LAYOUT_3 = 'layout_3';

    /**
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['value' => self::VALUE_DEFAULT_LAYOUT, 'label' => __('Default Layout')],
                ['value' => self::VALUE_LAYOUT_1, 'label' => __('Blog Layout 1')],
                ['value' => self::VALUE_LAYOUT_2, 'label' => __('Blog Layout 2')],
                ['value' => self::VALUE_LAYOUT_3, 'label' => __('Blog Layout 3')]
            ];
        }
        return $this->_options;
    }
}
