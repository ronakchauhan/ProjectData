<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Plugin;

use Amasty\Shopby\Helper\FilterSetting as FilterHelper;
use Amasty\Shopby\Api\Data\FilterSettingInterface;
use Amasty\Shopby\Model\OptionSetting;
use Amasty\Shopby\Model\FilterSetting;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class BlockHtmlTitlePlugin
{
    const IMAGE_URL     = 'image_url';
    const IMAGE_PATH    = 'image_path';
    const TITLE         = 'title';

    /** @var \Magento\Framework\Registry  */
    private $registry;

    /** @var \Magento\Store\Model\StoreManagerInterface  */
    private $storeManager;

    /** @var \Magento\Framework\View\Element\BlockFactory  */
    private $blockFactory;

    /** @var \Amasty\Shopby\Model\ResourceModel\FilterSetting\Collection  */
    private $filterCollection;

    /** @var \Amasty\Shopby\Model\ResourceModel\OptionSetting\Collection  */
    private $optionCollection;

    /** @var Configurable  */
    private $configurableType;

    public function __construct(
        \Amasty\Shopby\Model\ResourceModel\FilterSetting\CollectionFactory $filterCollectionFactory,
        \Amasty\Shopby\Model\ResourceModel\OptionSetting\CollectionFactory $optionCollectionFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Configurable $configurableType
    ) {
        $this->filterCollection = $filterCollectionFactory->create();
        $this->optionCollection = $optionCollectionFactory->create();
        $this->registry = $registry;
        $this->storeManager = $storeManager;
        $this->blockFactory = $blockFactory;
        $this->configurableType = $configurableType;
    }

    /**
     * Add Brand Label to Product Page
     *
     * @param \Magento\Theme\Block\Html\Title $original
     * @param $html
     * @return string
     */
    public function afterToHtml(
        \Magento\Theme\Block\Html\Title $original,
        $html
    ) {
        $optionsData = $this->getOptionsData();

        if (!count($optionsData)) {
            return $html;
        }

        $block = $this->blockFactory->createBlock('\Magento\Framework\View\Element\Template')
            ->setData('options_data', $optionsData)
            ->setTemplate('Amasty_ShopbyBrand::brand_icon.phtml');

        $count = 1;
        $html = str_replace('/h1>', '/h1>' . $block->toHtml(), $html, $count);

        return $html;
    }

    /**
     * @return array
     */
    private function getAttributeCodes()
    {
        $filtersToShow = $this->filterCollection
            ->addFieldToSelect(OptionSetting::FILTER_CODE)
            ->addFieldToFilter(FilterSettingInterface::SHOW_ICONS_ON_PRODUCT, true);
        $attributeCodes = [];
        foreach ($filtersToShow as $filter) {
            /** @var FilterSetting $filter */
            $attributeCodes[] = substr($filter->getFilterCode(), strlen(FilterHelper::ATTR_PREFIX));
        }
        return $attributeCodes;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    private function getAttributeValues(\Magento\Catalog\Model\Product $product)
    {
        $values = [];
        $attributeCodes = $this->getAttributeCodes();
        if (!count($attributeCodes)) {
            return $values;
        }

        foreach ($attributeCodes as $code) {
            $data = $product->getData($code);
            if (!$data && $product->getTypeId() === Configurable::TYPE_CODE) {
                /** @var \Magento\Catalog\Model\Product[] $childProducts */
                $childProducts = $this->configurableType->getUsedProducts($product);
                foreach ($childProducts as $childProduct) {
                    if ($childProduct->getData($code)) {
                        $values = array_merge($values, explode(',', $childProduct->getData($code)));
                    }
                }
            } elseif($data) {
                $values = array_merge($values, explode(',', $data));
            }
        }

        return $values;
    }

    /**
     * @return array
     */
    private function getOptionsData()
    {
        $data = [];
        $product = $this->registry->registry('current_product');
        if (!$product) {
            return $data;
        }

        $attributeValues = $this->getAttributeValues($product);
        if (!count($attributeValues)) {
            return $data;
        }

        $optionCollection = $this->optionCollection
            ->addFieldToSelect(OptionSetting::TITLE)
            ->addFieldToSelect(OptionSetting::VALUE)
            ->addFieldToSelect(OptionSetting::SLIDER_IMAGE)
            ->addFieldToSelect(OptionSetting::IMAGE)
            ->addFieldToSelect(OptionSetting::FILTER_CODE)
            ->addFieldToFilter(OptionSetting::VALUE, ['in' => $attributeValues])
            ->addFieldToFilter(
                [OptionSetting::SLIDER_IMAGE, OptionSetting::IMAGE],
                [['neq' => ''],['neq' => '']]
            );

        //default_store options will be rewritten with current_store ones.
        $optionCollection->getSelect()->order(['filter_code ASC', 'store_id ASC']);

        foreach ($optionCollection as $option) {
            /** @var OptionSetting $option */
            $data[$option->getValue()][self::IMAGE_URL] = $option->getSliderImageUrl();
            $data[$option->getValue()][self::IMAGE_PATH] = $option->getImagePath(true);
            $data[$option->getValue()][self::TITLE] = $option->getTitle();
        }

        return $data;
    }
}
