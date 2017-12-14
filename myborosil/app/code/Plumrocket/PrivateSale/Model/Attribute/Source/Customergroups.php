<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket Private Sales and Flash Sales v4.x.x
 * @copyright   Copyright (c) 2017 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


namespace Plumrocket\PrivateSale\Model\Attribute\Source;

use Magento\Customer\Model\Group;

class Customergroups extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
    * Customer Group
    *
    * @var \Magento\Customer\Model\ResourceModel\Group\Collection
    */
    protected $customerGroups;

    /**
     * Constructor
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     */
    public function __construct(
    	\Magento\Customer\Model\ResourceModel\Group\Collection $customerGroups
    ) {
    	$this->customerGroups = $customerGroups;
    }

    /**
     * Retrieve all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $customerGroup = $this->customerGroups->toOptionArray();
        unset($customerGroup[Group::NOT_LOGGED_IN_ID]);
        return $customerGroup;
    }
}
