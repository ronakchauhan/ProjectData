<?php

namespace Ktpl\PincodeSearch\Block;

class Search extends \Magento\Framework\View\Element\Template
{
	public function getSubmitUrl()
	{
		return $this->getUrl("pincodesearch/search/index");
	}
}