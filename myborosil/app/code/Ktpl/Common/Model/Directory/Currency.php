<?php

namespace Ktpl\Common\Model\Directory;

class Currency extends \Magento\Directory\Model\Currency
{
    public function formatTxt($price, $options = [])
    {
        if (!is_numeric($price)) {
            $price = $this->_localeFormat->getNumber($price);
        }
        $price = sprintf("%F", $price);

        $options['precision'] = 0;

        return $this->_localeCurrency->getCurrency($this->getCode())->toCurrency($price, $options);
    }
}