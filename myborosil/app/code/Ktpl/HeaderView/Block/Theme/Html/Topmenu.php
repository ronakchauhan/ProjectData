<?php

namespace Ktpl\HeaderView\Block\Theme\Html;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\ObjectManagerInterface;

class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{
    /**
     * @var CategoryRepositoryInterface $categoryRepository
     */
    protected $categoryRepository;
	
	protected $_customerSession;
	protected $_object;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Data\Tree\NodeFactory $nodeFactory
     * @param \Magento\Framework\Data\TreeFactory $treeFactory
     * @param CategoryRepositoryInterface $categoryRepository repository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
		\Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Tree\NodeFactory $nodeFactory,
        \Magento\Framework\Data\TreeFactory $treeFactory,
		ObjectManagerInterface $interface,
        CategoryRepositoryInterface $categoryRepository,
        array $data = []
    ) {
        $this->categoryRepository = $categoryRepository;
		$this->_customerSession = $customerSession;
		$this->_object = $interface;
        parent::__construct($context, $nodeFactory, $treeFactory, $data);
    }
	
	/**
     * Check customer is logged in or not
     * @var $_SESSION['customer_base']['customer_id'] 
     * used to resolve confliction from magento FPC
     * @return boolean
     */
    public function getCustomerLoggedIn()
    {
        $login = false;
        $_customCustomerSession = $this->getCustomerSession();
        $_customerId = $_customCustomerSession->getCustomerId();
        $customerSession = $this->_customerSession;
        if($customerSession->isLoggedIn() || $_customerId) {
            $login = true;
        }
        
        return $login;
    }
	
	public function getCustomerSession(){
        $customerSession = $this->_object->create('Magento\Customer\Model\SessionFactory')->create();
        return $customerSession;     
    }

    /**
     * Returns array of menu item's classes
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return array
     */
    protected function _getMenuItemClasses(\Magento\Framework\Data\Tree\Node $item)
    {
        $classes = [];

        $classes[] = 'level' . $item->getLevel();
        $classes[] = $item->getPositionClass();

        if ($item->getIsFirst()) {
            $classes[] = 'first';
        }

        if ($item->getIsActive()) {
            $classes[] = 'active';
        } elseif ($item->getHasActive()) {
            $classes[] = 'has-active';
        }

        if ($item->getIsLast()) {
            $classes[] = 'last';
        }

        if ($item->getClass()) {
            $classes[] = $item->getClass();
        }

        if ($item->hasChildren()) {
            $classes[] = 'parent';
        }

        if($this->isCategory($item->getId()) && $this->getCategoryData($item->getId())) {
            $classes[] = 'mega-menu-item';
        }

        return $classes;
    }

	/**
     * Add sub menu HTML code for current menu item
     *
     * @param \Magento\Framework\Data\Tree\Node $child
     * @param string $childLevel
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string HTML code
     */
    protected function _addSubMenu($child, $childLevel, $childrenWrapClass, $limit)
    {
        $html = '';
        if (!$child->hasChildren()) {
            return $html;
        }

        $colStops = null;
        if ($childLevel == 0 && $limit) {
            $colStops = $this->_columnBrake($child->getChildren(), $limit);
        }

        $html .= '<ul class="level' . $childLevel . ' submenu">';
        $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);

        if($this->isCategory($child->getId()) && $categoryData = $this->getCategoryData($child->getId())) {
            $html .= '<li class="level' . ($childLevel + 1) . ' mega-menu-image-wrapper">';

            $html .= '<div class="image"><img src="' . $categoryData['image'] . '"/></div>';
            if(isset($categoryData['description']) && trim($categoryData['description']) != "")
                $html .= '<div class="description">' . $categoryData['description'] . '</div>';

            $html .= '</li>';
        }
        
        $html .= '</ul>';

        return $html;
    }

    /**
     * Retrieves the category image for the corresponding child
     *
     * @param string $categoryId Category composed ID
     *
     * @return string
     */
    protected function getCategoryData($categoryId)
    {
        $categoryIdElements = explode('-', $categoryId);
        $category           = $this->categoryRepository->get(end($categoryIdElements));
        $categoryImage       = $category->getImageUrl();

        if(isset($categoryImage) && $categoryImage) {
            return ['image' => $categoryImage, 'description' => $category->getDescription()];
        }
 
        return false;
    }
 
    /**
     * Check if current menu element corresponds to a category
     *
     * @param string $menuId Menu element composed ID
     *
     * @return string
     */
    protected function isCategory($menuId)
    {
        $menuId = explode('-', $menuId);
 
        return 'category' == array_shift($menuId);
    }
}