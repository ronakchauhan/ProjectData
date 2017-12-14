<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Additional\Block\Cart;


/**
 * Cart crosssell list
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Crosssell extends \Magento\Checkout\Block\Cart\Crosssell
{
    /**
     * Items quantity will be capped to this value
     *
     * @var int
     */
    protected $_maxItemCount = 20;

}
