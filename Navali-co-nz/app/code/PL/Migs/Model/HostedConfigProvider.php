<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */

namespace PL\Migs\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\UrlInterface;
use Magento\Payment\Helper\Data as PaymentHelper;
use PL\Migs\Helper\Data as MigsHelper;

class HostedConfigProvider implements ConfigProviderInterface
{

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;


    protected $migsHelper;

    /**
     * @var string[]
     */
    protected $methodCodes = [
        'migs_hosted'
    ];

    /**
     * @var \Magento\Payment\Model\Method\AbstractMethod[]
     */
    protected $methods = [];

    /**
     * @var PaymentHelper
     */
    protected $paymentHelper;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param CurrentCustomer $currentCustomer
     * @param MigsHelper $migsHelper
     * @param PaymentHelper $paymentHelper
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        CurrentCustomer $currentCustomer,
        MigsHelper $migsHelper,
        PaymentHelper $paymentHelper,
        UrlInterface $urlBuilder
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->migsHelper = $migsHelper;
        $this->paymentHelper = $paymentHelper;
        $this->urlBuilder = $urlBuilder;

        foreach ($this->methodCodes as $code) {
            $this->methods[$code] = $this->paymentHelper->getMethodInstance($code);
        }

    }

    public function getConfig()
    {
        $config = [
            'payment' => [
                'migsHosted' => []
            ]
        ];

        foreach ($this->methodCodes as $code) {
            if ($this->methods[$code]->isAvailable()) {
                $config['payment']['migsHosted']['redirectUrl'] = $this->getMethodRedirectUrl($code);
            }
        }

        return $config;
    }

    protected function getMethodRedirectUrl($code)
    {
        return $this->methods[$code]->getCheckoutRedirectUrl();
    }
}
