<?php

namespace Ktpl\Common\Plugin;

class FormatPrice
{
	public function aroundGetPriceFormat(\Magento\Framework\Locale\Format $subject, callable $proceed, $localeCode = null, $currencyCode = null)
    {
        $returnValue = $proceed($localeCode, $currencyCode);

        $returnValue['precision'] = 0;
        $returnValue['requiredPrecision'] = 0;

        return $returnValue;
    }
}