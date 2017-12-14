<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Model\Source\Attribute;

use Magento\Framework\Option\ArrayInterface;
use Magento\Eav\Model\Config as EavConfig;

class OptionGrid implements ArrayInterface
{

    /**
     * @var array
     */
    protected $options;

    /**
     * @var Option
     */
    protected $option;

    /**
     * OptionGrid constructor.
     * @param Option $option
     */
    public function __construct(
        Option $option
    ){
       $this->option = $option;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {

        if ($this->options === null) {
            $data  =$this->option->toExtendedArray();
            $options = [];
            foreach($data as $code => $value) {
                $scope = $value['options'];
                foreach($scope as &$item) {
                    $item['code'] = $code;
                    $item['swatch'] = ($item['type'] == 2) ? 'background-image:url('. $item['swatch'] .');background-size:cover'
                        : (($item['type'] == 1) ? 'background:'. $item['swatch'] : $item['swatch']);
                }

                $options = array_merge($options,$scope);
            }
            $this->options  = $options;
        }

        return $this->options;
    }
}
