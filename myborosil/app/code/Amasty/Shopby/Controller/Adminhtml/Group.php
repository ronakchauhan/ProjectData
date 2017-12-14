<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Controller\Adminhtml;

abstract class Group extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Amasty_Shopby::group_attributes';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Amasty\Shopby\Model\GroupAttrFactory
     */
    protected $groupAttrFactory;

    /**
     * @var \Magento\Backend\Model\SessionFactory
     */
    protected $sessionFactory;

    /**
     * Group constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Amasty\Shopby\Model\GroupAttrFactory $groupAttrFactory
     * @param \Magento\Backend\Model\SessionFactory $sessionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Amasty\Shopby\Model\GroupAttrFactory $groupAttrFactory,
        \Magento\Backend\Model\SessionFactory $sessionFactory
    )
    {
        $this->coreRegistry = $coreRegistry;
        $this->groupAttrFactory = $groupAttrFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->sessionFactory = $sessionFactory;
        parent::__construct($context);
    }
}
