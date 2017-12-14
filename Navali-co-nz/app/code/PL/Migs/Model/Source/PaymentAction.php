<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 5/10/2016
 * Time: 2:05 AM
 */
namespace PL\Migs\Model\Source;

use /** @noinspection PhpDeprecationInspection */
    \Magento\Payment\Model\Method\AbstractMethod;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
class PaymentAction implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Possible actions on order place
     *
     * @return array
     */
    public function toOptionArray()
    {
        /** @noinspection PhpDeprecationInspection */
        return [
            [
                'value' => AbstractMethod::ACTION_AUTHORIZE,
                'label' => __('Authorize'),
            ],
            [
                'value' => AbstractMethod::ACTION_AUTHORIZE_CAPTURE,
                'label' => __('Authorize and Capture'),
            ]
        ];
    }
}
