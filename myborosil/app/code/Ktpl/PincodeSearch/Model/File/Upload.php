<?php

namespace Ktpl\PincodeSearch\Model\File;

class Upload
{
	public function aroundCheckAllowedExtension(\Magento\Framework\File\Uploader $subject, callable $proceed , $extension)
	{
		if($extension == 'csv')
		{
			$returnValue = true;
		}
		else
		{
			$returnValue = false;
		}

		return $returnValue;
	}
}