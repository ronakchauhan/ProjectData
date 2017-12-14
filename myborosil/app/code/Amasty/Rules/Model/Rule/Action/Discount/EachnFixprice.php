<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Rules
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */
namespace Amasty\Rules\Model\Rule\Action\Discount;

class EachnFixprice extends AbstractRule
{
    const RULE_VERSION = '1.0.0';

    public function __construct(
        \Magento\SalesRule\Model\Validator $validator,
        \Magento\SalesRule\Model\Rule\Action\Discount\DataFactory $discountDataFactory,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Amasty\Rules\Helper\Product $rulesProductHelper,
        \Amasty\Rules\Helper\Data $rulesDataHelper,
        \Amasty\Rules\Helper\Discount $rulesDiscountHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct(
            $validator,
            $discountDataFactory,
            $priceCurrency,
            $objectManager,
            $storeManager,
            $rulesProductHelper,
            $rulesDataHelper,
            $rulesDiscountHelper,
            $customerSession,
            $scopeConfig
        );
    }

    /**
     * @param \Magento\SalesRule\Model\Rule $rule
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     * @param float $qty
     * @return \Magento\SalesRule\Model\Rule\Action\Discount\Data Data
     */
    public function calculate($rule, $item, $qty)
    {
        $this->beforeCalculate($rule, $item, $qty);
        $discountData = $this->_calculate($rule, $item);
        $this->afterCalculate($discountData, $rule, $item);
        return $discountData;
    }

    /**
     * @param \Magento\SalesRule\Model\Rule $rule
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     * @return \Magento\SalesRule\Model\Rule\Action\Discount\Data Data
     */
    protected function _calculate($rule, $item)
    {
        /** @var \Magento\SalesRule\Model\Rule\Action\Discount\Data $discountData */
        $discountData = $this->discountFactory->create();
        $allItems = $this->getSortedItems($item->getAddress(), $rule, 'desc');
        $allItems = $this->skipEachN($allItems, $rule);
        $itemsId = $this->getItemsId($allItems);
        /** @var \Magento\Quote\Model\Quote\Item\AbstractItem $allItem */
        foreach ($allItems as $i => $allItem) {
            if (in_array($item->getAmrulesId(), $itemsId) && $allItem->getAmrulesId()===$item->getAmrulesId()) {
                $itemQty = $this->getArrayValueCount($itemsId, $item->getAmrulesId());
                $itemPrice = $this->rulesProductHelper->getItemPrice($item);
                $baseItemPrice = $this->rulesProductHelper->getItemBasePrice($item);
                $baseAmount = $baseItemPrice - $rule->getDiscountAmount();
                $quoteAmount = $this->priceCurrency->convert($rule->getDiscountAmount(), $item->getQuote()->getStore());
                $quoteAmount = $itemPrice - $quoteAmount;
                $itemBaseOriginalPrice = $this->rulesProductHelper->getItemBaseOriginalPrice($item);
                $baseOriginalAmount = $itemBaseOriginalPrice - $rule->getDiscountAmount();
                $itemOriginalPrice = $this->rulesProductHelper->getItemOriginalPrice($item);
                $originalAmount = $itemOriginalPrice - $quoteAmount;
                $discountData->setAmount($itemQty * $quoteAmount);
                $discountData->setBaseAmount($itemQty * $baseAmount);
                $discountData->setOriginalAmount($itemQty * $originalAmount);
                $discountData->setBaseOriginalAmount($itemQty * $baseOriginalAmount);
            }
        }

        return $discountData;
    }
}
