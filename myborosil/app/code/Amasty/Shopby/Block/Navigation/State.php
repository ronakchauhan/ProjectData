<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Block\Navigation;


class State extends \Magento\LayeredNavigation\Block\Navigation\State
{
    /**
     * @var string
     */
    protected $_template = 'layer/state.phtml';

    /** @var \Amasty\Shopby\Helper\FilterSetting  */
    protected $filterSettingHelper;

    protected $managerInterface;

    protected $priceCurrency;


    /**
     * State constructor.
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Amasty\Shopby\Helper\FilterSetting $filterSettingHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Amasty\Shopby\Helper\FilterSetting $filterSettingHelper,
        array $data = []
    )
    {
        $this->filterSettingHelper = $filterSettingHelper;
        $this->managerInterface = $context->getStoreManager();
        $this->priceCurrency = $priceCurrency;
        parent::__construct($context, $layerResolver, $data);
    }

    /**
     * @param \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter
     * @return \Amasty\Shopby\Api\Data\FilterSettingInterface
     */
    public function getFilterSetting(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter)
    {
        return $this->filterSettingHelper->getSettingByLayerFilter($filter);
    }

    /**
     * @param \Amasty\Shopby\Model\Layer\Filter\Item $filter
     * @param bool|false $showLabels
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSwatchHtml(\Amasty\Shopby\Model\Layer\Filter\Item $filter, $showLabels = false)
    {
        return $this->getLayout()->createBlock(
                    'Amasty\Shopby\Block\Navigation\State\Swatch'
                )
                ->setFilter($filter)
                ->showLabels($showLabels)
                ->toHtml();
    }

    /**
     * @return string
     */
    public function collectFilters()
    {
        return $this->_scopeConfig->getValue(
            \Amasty\Shopby\Block\Navigation\FilterRenderer::XML_CONFIG_SUBMIT_FILTER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ) === \Amasty\Shopby\Model\Source\SubmitMode::BY_BUTTON_CLICK ? '1' : '0';
    }

    /**
     * @param $filter
     * @return mixed|string
     */
    public function viewLabel($filter)
    {
        $filterSetting = $this->getFilterSetting($filter->getFilter());

        switch($filterSetting->getDisplayMode())
        {
            case \Amasty\Shopby\Model\Source\DisplayMode::MODE_IMAGES:
                $value =  $this->getSwatchHtml($filter);
                break;
            case \Amasty\Shopby\Model\Source\DisplayMode::MODE_IMAGES_LABELS:
                $value =  $this->getSwatchHtml($filter, true);
                break;
            default:
              $value = $this->viewExtendedLabel($filter);
                break;
        }

        return $value;
    }

    /**
     * @param $filter
     * @return null|string
     */
    protected function viewExtendedLabel($filter)
    {
        $value = null;
        $currencyRate = $this->managerInterface
            ->getStore($this->_storeManager->getStore()->getId())
            ->getCurrentCurrencyRate();
        $filterValues = $filter->getValue();
        if ($filter->getFilter()->getRequestVar() == \Amasty\Shopby\Model\Source\DisplayMode::ATTRUBUTE_PRICE) {
            $value = $this->stripTags($this->priceCurrency->format($filterValues[0] * $currencyRate) .
                ' - ' . $this->priceCurrency->format($filterValues[1] * $currencyRate));
        } else {
            $value = $this->stripTags($filter->getLabel());
        }

        return $value;
    }
}
