<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Additional\Model\Order;

use Magento\Customer\Api\Data\RegionInterfaceFactory;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Model\AbstractModel;
use Magento\Customer\Model\Address\AddressModelInterface;

/**
 * Sales order address model
 *
 * @method \Magento\Sales\Model\ResourceModel\Order\Address _getResource()
 * @method \Magento\Sales\Model\ResourceModel\Order\Address getResource()
 * @method \Magento\Customer\Api\Data\AddressInterface getCustomerAddressData()
 * @method Address setCustomerAddressData(\Magento\Customer\Api\Data\AddressInterface $value)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class Address extends \Magento\Sales\Model\Order\Address
{
    public function getSuburb($suburb)
    {
         return $this->getData('suburb', $suburb);
    }

    public function setSuburb($suburb)
    {
         return $this->setData('suburb', $suburb);
    }
}
