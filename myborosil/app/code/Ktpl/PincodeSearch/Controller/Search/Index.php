<?php

namespace Ktpl\PincodeSearch\Controller\Search;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $pincodeFactory;
    public $_storeManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Ktpl\PincodeSearch\Model\PincodeFactory $pincodeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->pincodeFactory = $pincodeFactory;
        $this->_storeManager=$storeManager;
    }

    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        $contactUrl = $this->_storeManager->getStore()
           ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK) . "contact";
        $data = "";
        
        $data = ['message' => 'This item is not in stock in your area. <a href="' . $contactUrl . '">Click here</a> to send us your contact information so we can let you know when it is.','success' => 'invalid'];

        if(isset($post['pincode']))
        {
            $pincodeObject = $this->pincodeFactory->create()
                ->loadByPincode($post['pincode']);
            if($pincodeObject->getId() && $pincodeObject->getIsActive() && $pincodeObject->getIsCodAvailable())
            {
                $data = ['message' => 'In stock. COD is available in ' . $post['pincode'] .'.
                Prepaid orders are delivered in 1-2 business days <a href="javascript:void(0)" class="pincode-search-visible">Click here</a> to check for another pincode.<div class="pincode-main-helpbox">[<a href="javascript:void(0)"  class="pincode-search-help" data-tip="help">?</a>]<div class="picode-popup-text hide help"><ul><li>Business days exclude public holidays and weekends</li><li>Delivery estimation not applicable to COD deliveries - COD devliveries take longer than prepaid shipment.</li></ul></div></div>', 'success' => 'true'];
            }
            elseif($pincodeObject->getId() && $pincodeObject->getIsActive()){
                $data = ['message' => 'In stock. COD is not available in ' . $post['pincode'] .'.
                Prepaid orders are delivered in 1-2 business days
                <a href="javascript:void(0)"  class="pincode-search-visible">Click here</a> to check for another pincode.', 'success' => 'false'];
            }
        }

        $resultJson = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);
        $resultJson->setData($data);
        return $resultJson;
    }
}
