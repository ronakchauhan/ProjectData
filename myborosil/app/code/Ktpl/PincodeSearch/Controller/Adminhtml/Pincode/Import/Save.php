<?php

namespace Ktpl\PincodeSearch\Controller\Adminhtml\Pincode\Import;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Ktpl_PincodeSearch::manage_pincodes';

    /**
     * Image uploader
     *
     * @var \Ktpl\BannerSlider\BannerImageUploader
     */
    private $csvUploader;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * CSV Processor
     *
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * @param Magento\Backend\App\Action\Context $context
     * @param Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\File\Csv $csvProcessor
    ) {
        $this->_filesystem = $filesystem;
        $this->_storeManager = $storeManager;
        $this->csvProcessor = $csvProcessor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            if(isset($data['file_upload']['0'])) {
                $data['file_upload'] = $data['file_upload']['0']['name'];
            }
            else {
                $data['file_upload'] = null;
            }

            if(isset($data['file_upload']) && !is_null($data['file_upload']))
            {
                $this->getCSVUploader()->moveFileFromTmp($data['file_upload']);

                $mediaPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath()
                     . 'pincodesearch/pincode/' . $data['file_upload'];

                $importProductRawData = $this->csvProcessor->getData($mediaPath);

                $count = 0;

                foreach ($importProductRawData as $rowIndex => $dataRow) 
                {
                    if($rowIndex > 0) 
                    {
                        $model = $this->_objectManager->create('Ktpl\PincodeSearch\Model\Pincode');
                        
                        $model->loadByPincode($dataRow[0])
                            ->setData('pincode', $dataRow[0])
                            ->setData('is_cod_available', $dataRow[1])
                            ->setData('delivery_time', $dataRow[2])
                            ->setData('delivery_message', $dataRow[3])
                            ->setData('is_active', $dataRow[4])
                            ->save();

                        $count++;
                    }
                }
                
                $this->messageManager->addSuccess(__('Total %1 pincodes added / updated successfully.', $count));
            }
            else
                $this->messageManager->addError(__('CSV file not uploaded properly, please try again!'));
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        return $resultRedirect->setPath('*/pincode/index');
    }

    /**
     * Get image uploader
     *
     * @return \Ktpl\BannerSlider\BannerImageUploader
     *
     * @deprecated
     */
    private function getCSVUploader()
    {
        if ($this->csvUploader === null) {
            $this->csvUploader = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Ktpl\PincodeSearch\CsvUploader'
            );
        }
        return $this->csvUploader;
    }
}
