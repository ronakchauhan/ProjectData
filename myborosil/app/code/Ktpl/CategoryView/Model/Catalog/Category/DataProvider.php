<?php

namespace Ktpl\CategoryView\Model\Catalog\Category;

/**
 * Class DataProvider
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{
	protected function getFieldsMap()
    {
    	$fieldMap = parent::getFieldsMap();
		$fieldMap['list_block_settings'][] = 'listing_layout';
		$fieldMap['list_block_settings'][] = 'block_layout';
		$fieldMap['display_settings'][]    = 'group_sort';
		
		$fieldMap['content'][]             = 'banner_block';		
		
		return $fieldMap;
    }
}

