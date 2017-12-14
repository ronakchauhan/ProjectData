<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */
namespace Ktpl\ImprovedLayeredNavigation\Block\Navigation\FilterCollapsing;


class Category extends \Magento\Framework\View\Element\Template
{
	protected $category;

	protected $categoryRepository;

	protected $subCategories;

	private $registry;

	/**
	 * Recipient email config path
	 */
    const XML_PATH_SELECTED_CATEGORIES = 'improvedlayerednavigation/general/categories';


	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Category $category,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->category = $category;
        $this->categoryRepository = $categoryRepository;
        $this->registry = $registry;
        $this->subCategories = ['filterChildCategories' => []];
        parent::__construct($context, $data);
    }

    public function getCategories()
    {
    	$category = $this->registry->registry('current_category');

		$pathIds = $category->getPathIds();

        $pathCount = count($pathIds) - 2;

    	$categoryObj = $this->categoryRepository->get($pathIds[$pathCount]);
        $subcategories = $categoryObj->getChildrenCategories();
        $cateogories = ['filterCategories' => []];
        $categoryCount = 0;
        foreach($subcategories as $subcategory) {
            if ($subcategory->getLevel() >=  $category->getLevel()) {
                
                if ($subcategory->getId() != $category->getId()) 
                {            
    	            if ($subcategory->getChildrenCount() == 0) {
    	            	$cateogories['filterCategories'][$categoryCount]['id'] = $subcategory->getId();
    	            	$cateogories['filterCategories'][$categoryCount]['url'] = $this->getUrl() . $subcategory->getRequestPath();
    	            	$cateogories['filterCategories'][$categoryCount]['name'] = $subcategory->getName();
    	            	$cateogories['filterCategories'][$categoryCount]['subcategories'] = '';
    	            	$categoryCount++;
    	            }
    	            else
    	            {
    	            	$cateogories['filterCategories'][$categoryCount]['id'] = $subcategory->getId();
    	            	$cateogories['filterCategories'][$categoryCount]['url'] = $this->getUrl() . $subcategory->getRequestPath();
    	            	$cateogories['filterCategories'][$categoryCount]['name'] = $subcategory->getName();
    	            	
    	            	$cateogories['filterCategories'][$categoryCount]['subcategories'] = $this->getChildrenCategories($subcategory->getId());
    					
    					$this->subCategories = array();
    					$categoryCount++;
    	            }
    	        }
            }
        }

        return $cateogories;
    }

    public function getChildrenCategories($categoryId)
    {
    	
    	$childcategoryObj = $this->categoryRepository->get($categoryId);
		$childsubcategories = $childcategoryObj->getChildrenCategories();
		$subcategoryCount = 0;

		foreach ($childsubcategories as $childsubcategory) {
            if ($childsubcategory->getChildrenCount() == 0) {
            	$this->subCategories['filterChildCategories'][$subcategoryCount]['id'] = $childsubcategory->getId();
            	$this->subCategories['filterChildCategories'][$subcategoryCount]['url'] = $this->getUrl() . $childsubcategory->getRequestPath();
            	$this->subCategories['filterChildCategories'][$subcategoryCount]['name'] = $childsubcategory->getName();
            	$this->subCategories['filterChildCategories'][$subcategoryCount]['subcategories'] = '';
            	$subcategoryCount++;
			}
			else{
				$this->subCategories['filterChildCategories'][$subcategoryCount]['id'] = $childsubcategory->getId();
            	$this->subCategories['filterChildCategories'][$subcategoryCount]['url'] = $this->getUrl() . $childsubcategory->getRequestPath();
            	$this->subCategories['filterChildCategories'][$subcategoryCount]['name'] = $childsubcategory->getName();
            	$this->subCategories['filterChildCategories'][$subcategoryCount]['subcategories'] = $this->getChildrenCategories($childsubcategory->getId());
			}	
   		}

   		return $this->subCategories;
   	}

    public function getCurrentCategory()
    {
        return $this->registry->registry('current_category');
    }
}