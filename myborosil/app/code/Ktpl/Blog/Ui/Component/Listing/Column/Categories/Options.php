<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Ktpl\Blog\Ui\Component\Listing\Column\Categories;

use Magento\Framework\Option\ArrayInterface;
use Ktpl\Blog\Model\CategoryFactory;
use Ktpl\Blog\Model\Category as BlogCategory;

class Options implements ArrayInterface
{
    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var array
     */
    protected $_path = [];

    protected $_emptyOption = true;

    /**
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(
        CategoryFactory $categoryFactory
    ){
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];
        $arr = $this->toArray();
        foreach($arr as $value => $label){
            $optionArray[] = [
                'value' => $value,
                'label' => $label
            ];
        }
        return $optionArray;
    }

    /**
     * Build path for particular category
     *
     * @return void
     */
    protected function _buildPath(BlogCategory $category)
    {
        echo"<pre/>"; print_r($category->getData());exit;
        if (
            $category->getTitle() &&
            intval($category->getCategoryId()) == \Ktpl\Blog\Model\Category::TREE_ROOT_ID
        ){
            $this->_path[] = array(
                'id'    => $category->getId(),
                'level' => $category->getLevel(),
                'name'  => $category->getTitle(),
            );
        }

        // echo"<pre/>"; print_r($this->_path);exit;

        if ($category->hasChildren())
        {
            foreach ($this->getChildrenCategories($category) as $child)
            {
                $this->_buildPath($child);
            }
        }
    }

    /**
     * Get children categories for particular category
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getChildrenCategories(BlogCategory $category)
    {

        $collection = $category->getCollection();
        /* @var $collection \Ktpl\Blogt\Model\ResourceModel\Category\Collection */
        $collection->addAttributeToSelect('url_key')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('all_children')
            ->addAttributeToFilter('is_active', 1)
            ->addFieldToFilter('parent_id', $category->getId())
            ->setOrder('position', 'asc')
            ->load();

        return $collection;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $parentCategory = $this->categoryFactory->create()
            ->load(\Ktpl\Blog\Model\Category::TREE_ROOT_ID);

        $this->_path = [];
        $this->_buildPath($parentCategory);

        $options = array();

        if ($this->_emptyOption) {
            $options[0] = ' ';
        }

        foreach ($this->_path as $i => $path)
        {
            $string = str_repeat(". ", max(0, ($path['level'] - 1) * 3)) . $path['name'];
            $options[$path['id']] = $string;
        }

        return $options;
    }

    /**
     * @param bool $emptyOption
     * @return $this
     */
    public function setEmptyOption($emptyOption)
    {
        $this->_emptyOption = $emptyOption;

        return $this;
    }
}