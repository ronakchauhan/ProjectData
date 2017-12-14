<?php

namespace Ktpl\ProductView\Model\Catalog\Product\Attribute\Source\View;

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
    const VALUE_LAYOUT_4 = 'layout_4';
    const VALUE_LAYOUT_5 = 'layout_5';
    const VALUE_LAYOUT_6 = 'layout_6';

    /**
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['value' => self::VALUE_DEFAULT_LAYOUT, 'label' => __('Default Layout')],
                ['value' => self::VALUE_LAYOUT_1, 'label' => __('DINNERWARE')],
                ['value' => self::VALUE_LAYOUT_2, 'label' => __('DRINKWARE')],
                ['value' => self::VALUE_LAYOUT_3, 'label' => __('STARTER KIT')],
                ['value' => self::VALUE_LAYOUT_4, 'label' => __('APPLIANCES')],
                ['value' => self::VALUE_LAYOUT_5, 'label' => __('STORAGE')],
                ['value' => self::VALUE_LAYOUT_6, 'label' => __('Dinner Configurable')]
            ];
        }
        return $this->_options;
    }
}
