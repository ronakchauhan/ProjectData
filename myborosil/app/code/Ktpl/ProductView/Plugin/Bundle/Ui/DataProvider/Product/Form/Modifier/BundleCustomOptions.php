<?php 

namespace Ktpl\ProductView\Plugin\Bundle\Ui\DataProvider\Product\Form\Modifier;

use Magento\Bundle\Ui\DataProvider\Product\Form\Modifier\BundlePanel;

class BundleCustomOptions
{
    /**
     * {@inheritdoc}
     */
    public function afterModifyMeta(
        \Magento\Bundle\Ui\DataProvider\Product\Form\Modifier\BundleCustomOptions $subject,
        $meta
    ) {
        $meta[BundlePanel::CODE_BUNDLE_DATA]['children'][BundlePanel::CODE_BUNDLE_OPTIONS]['children']['record']['children']['product_bundle_container']['children']['option_info']['children']['option_label'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Option Label'),
                        'componentType' => \Magento\Ui\Component\Form\Field::NAME,
                        'formElement' => \Magento\Ui\Component\Form\Element\Input::NAME,
                        'dataScope' => 'option_label',
                        'dataType' => \Magento\Ui\Component\Form\Element\DataType\Text::NAME,
                        'component' => 'Magento_Catalog/component/static-type-input',
                        'valueUpdate' => 'input',
                        'sortOrder' => 30,
                        'validation' => [
                            'required-entry' => false
                        ],
                        'imports' => [
                            'optionId' => '${ $.provider }:${ $.parentScope }.option_id'
                        ]
                    ],
                ],
            ],
        ];

        return $meta;

    }
}
