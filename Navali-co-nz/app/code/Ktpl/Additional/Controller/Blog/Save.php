<?php

namespace Ktpl\Additional\Controller\Blog;

use Magento\Framework\Controller\ResultFactory;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $_fileUploaderFactory;

    protected $_objectManager;
    
    protected $_filesystem;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Filesystem $filesystem
    ) {

        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_objectManager = $context->getObjectManager();
        $this->_filesystem = $filesystem;
        parent::__construct($context);
    }

    /**
     * Save Banner
     *
     * @return void
     */
    public function execute()
    {
        $storeId = $this->getRequest()->getParam('store', 0);
        $store = $this->getStoreManager()->getStore($storeId);
        $this->getStoreManager()->setCurrentStore($store->getCode());
        
        $post = $this->getRequest()->getPostValue();
         // echo"<pre/>"; print_r($_FILES);exit;
        if (!$post) {
            $this->_redirect($this->_redirect->getRefererUrl());
            return;
        }
        try {
            $rowData = $this->_objectManager->create('Ktpl\Additional\Model\Bags');
 
            if ($_FILES) 
            {
                $post['bag_image'] = $_FILES['bag_image']['name'];
            }

            $rowData->setData($post);  

            $uploader = $this->_fileUploaderFactory->create(['fileId' => 'bag_image']);
              
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
              
            $uploader->setAllowRenameFiles(false);
              
            $uploader->setFilesDispersion(false);
         
            $path = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('bagsimages/');
            
            $uploader->save($path);

            $rowData->save();  

            $this->messageManager->addSuccess(__('Your Bag request has been sent.'));
        } catch (Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect($this->_redirect->getRefererUrl());
    }

    /**
     * @return StoreManagerInterface
     * @deprecated
     */
    private function getStoreManager()
    {
        if (null === $this->storeManager) {
            $this->storeManager = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Store\Model\StoreManagerInterface');
        }
        return $this->storeManager;
    }

}
