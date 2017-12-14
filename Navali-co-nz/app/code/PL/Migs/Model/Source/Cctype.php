<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Migs\Model\Source;

class Cctype extends \Magento\Payment\Model\Source\Cctype
{

    /**
     * @return array
     */
    public function getAllowedTypes()
    {
        return ['VI', 'MC', 'AE', 'DI', 'JCB', 'OT'];
    }
}
