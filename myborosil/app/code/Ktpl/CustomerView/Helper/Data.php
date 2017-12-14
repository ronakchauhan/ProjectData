<?php

namespace Ktpl\CustomerView\Helper;

/**
 * Customer helper for view.
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        $this->_customerFactory = $customerFactory;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerAttributeValue($customerId, $attributeCode)
    {
        $customerObject = $this->_customerFactory->create()->load($customerId);
        return $customerObject->getData($attributeCode);
    }
}
