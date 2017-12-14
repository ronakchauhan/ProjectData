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
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\PrivateSale\Model;

class Image extends \Magento\Framework\Model\AbstractModel
{

	/**
	 * File System
	 * @var \Magento\Framework\FilesystemFactory
	 */
	protected $filesystem;

	/**
	 * Uploader
	 * @var  \Magento\MediaStorage\Model\File\UploaderFactory
	 */
	protected $uploader;

	/**
	 * File
	 * @var \Magento\Framework\Filesystem\Driver\File
	 */
	protected $file;

	/**
	 * Constructor
	 * @param \Magento\Framework\Model\Context                 $context
	 * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploader
	 * @param \Magento\Framework\Filesystem                    $filesystem
	 * @param \Magento\Framework\Filesystem\Driver\File        $file
	 * @param \Magento\Framework\Registry                      $registry
	 * @param array                                            $data
	 */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploader,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Driver\File $file,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
    	$this->filesystem = $filesystem;
    	$this->uploader = $uploader;
    	$this->file = $file;
    	parent::__construct(
    		$context,
    		$registry,
    		null,
    		null,
    		$data
    	);

	}

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Plumrocket\PrivateSale\Model\ResourceModel\Image');
    }

    /**
     * Save images
     * @param  Plumrocket_PrivateSale_Model_image $images
     * @return $this
     */
    public function saveImages($images)
    {
    	if (!$images) {
    		return $this;
    	}

    	try {
	    	$imageCollection = $this->getCollection()
	      		->addFieldToFilter('image_id', array_keys($images));

	      	foreach ($imageCollection as $img) {
	      		if (isset($images[$img->getId()]['remove'])) {
	      			$this->removeImage($img);
	      		} else {
	      			$_image = $images[$img->getId()];

		      		$img->setSortOrder((int)$_image['sort_order'])
		      			->setActiveFrom($_image['active_from'])
		      			->setActiveTo($_image['active_to'])
		      			->setExclude(isset($_image['exclude']) ? 1 : 0)
		      			->save();
	      		}
	      	}
	    } catch (\Exception $e) {
	    }
    }

	/**
	 * Remove image
	 * @param  Plumrocket_PrivateSale_Model_Image $image
	 * @return $this
	 */
	private function removeImage($image)
	{
		$mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
		$mediaRootDir = $mediaDirectory->getAbsolutePath();

		if (!$image || !$image->getImageId() || !$image->getName()) {
			throw new \Exception('Image name is missing');
		}

		$imagePath = $mediaRootDir . 'splashpage' . $image->getName();

		if ($this->file->isExists($imagePath))  {
		    $this->file->deleteFile($imagePath);
		}

		$image->delete();
		return $this;
	}

	/**
	 * Load files
	 * @return void
	 */
	public function loadFiles()
	{
		/* Prepare featured image */
        $imageField = 'files';
        $mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

        $i = 1;
        while (true) {

	        if (isset($data[$imageField]) && isset($data[$imageField]['value'])) {
	            if (isset($data[$imageField]['delete'])) {
	                unlink($mediaDirectory->getAbsolutePath() . $data[$imageField]['value']);
	                $model->setData($imageField, '');
	            } else {
	                $model->unsetData($imageField);
	            }
	        }
	        try {
	            $uploader = $this->uploader->create(['fileId' => 'files_'.$i.'']);
	            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
	            $uploader->setAllowRenameFiles(true);
	            $uploader->setFilesDispersion(true);
	            $uploader->setAllowCreateFolders(true);
	            $result = $uploader->save(
	                $mediaDirectory->getAbsolutePath('splashpage')
	            );
	            $i++;


	            $this->setData(
	            	[
	            		'name' => $result['file']
	            	]
	            )->save();

	            // $model->setData($imageField, 'tset' . $result['file']);
	        } catch (\Exception $e) {
	            if ($e->getCode() != \Magento\Framework\File\Uploader::TMP_NAME_EMPTY) {
	                throw new \Exception($e->getMessage());
	            } else {
	            	break;
	            }
	        }
	    }
	}
}
