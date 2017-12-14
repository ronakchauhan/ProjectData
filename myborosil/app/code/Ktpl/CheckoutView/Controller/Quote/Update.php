<?php
/**
 * *
 *  Copyright © 2016 Magestore. All rights reserved.
 *  See COPYING.txt for license details.
 *  
 */

namespace Ktpl\CheckoutView\Controller\Quote;
/**
 * Class Update
 * @package Ktpl\CheckoutView\Controller\Quote
 */
class Update extends \Magento\Framework\App\Action\Action {
    /**
     * @var \Magento\Checkout\Model\Sidebar
     */
    protected $_sidebar;
    
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $_dataObjectFactory;
    /**
     * @var \Magento\Quote\Api\CartTotalRepositoryInterface
     */
    protected $_cartTotalRepositoryInterface;

    /**
     * @var \Ktpl\CheckoutView\Helper\Data
     */
    protected $_oscHelper;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * @var \Ktpl\CheckoutView\Helper\Config
     */
    protected $_configHelper;

    /**
     * Update constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalRepositoryInterface
     * @param \Magento\Checkout\Model\Sidebar $sidebar
     * @param \Ktpl\CheckoutView\Helper\Data $oscHelper
     * @param \Ktpl\CheckoutView\Helper\Config $configHelper
     * @param \Magento\Checkout\Model\Cart $cart
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalRepositoryInterface,
        \Magento\Checkout\Model\Sidebar $sidebar,
        \Ktpl\CheckoutView\Helper\Data $oscHelper,
        \Ktpl\CheckoutView\Helper\Config $configHelper,
        \Magento\Checkout\Model\Cart $cart
    ) {
        parent::__construct($context);
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_jsonHelper = $jsonHelper;
        $this->_dataObjectFactory = $dataObjectFactory;
        $this->_sidebar = $sidebar;
        $this->_cartTotalRepositoryInterface = $cartTotalRepositoryInterface;
        $this->_oscHelper = $oscHelper;
        $this->_configHelper = $configHelper;
        $this->cart = $cart;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        /** @var \Magento\Framework\DataObject $qtyData */
        $qtyData = $this->_dataObjectFactory->create([
            'data' => $this->_jsonHelper->jsonDecode($this->getRequest()->getContent()),
        ]);
        
        $updateType = $qtyData->getData('updateType');
        $result = array();
        $result['error'] = '';
        $item = $this->cart->getQuote()->getItemById($qtyData->getData('itemId'));
        $oldQty = $item->getQty();
        try {
            if ($updateType == 'update') {
                $this->_sidebar->checkQuoteItem($qtyData->getData('itemId'));
                $this->_sidebar->updateQuoteItem($qtyData->getData('itemId'), $qtyData->getData('qty'));
            } else {
                $this->_sidebar->removeQuoteItem($qtyData->getData('itemId'));
            }

        } catch (\Exception $e) {
            $this->_sidebar->updateQuoteItem($qtyData->getData('itemId'), $oldQty);
            $result['error'] = $e->getMessage();
        }

        if ($this->_configHelper->isEnableGiftWrap()) {
            $giftWrapAmount = $this->_oscHelper->getOrderGiftWrapAmount();
            $result['giftwrap_amount'] = $giftWrapAmount;
        } 
        if($this->cart->getSummaryQty() == 0){
            $result['cartEmpty'] = true;
        }

        if ($this->cart->getQuote()->isVirtual()) {
            $result['is_virtual'] = true;
        } else {
            $result['is_virtual'] = false;
        }
        
        $resultJson = $this->_resultJsonFactory->create();
        return $resultJson->setData($result);
    }
}