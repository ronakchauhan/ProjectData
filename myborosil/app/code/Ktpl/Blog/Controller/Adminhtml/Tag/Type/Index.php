<?php
namespace Ktpl\Blog\Controller\Adminhtml\Tag\Type;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Ktpl\Blog\Controller\Adminhtml\Tag\Type
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ktpl_Blog::tag_type');
        $resultPage->addBreadcrumb(__('Tag Type'), __('Tag Type'));
        $resultPage->addBreadcrumb(__('Manage Tag Type'), __('Manage Tag Type'));
        $resultPage->getConfig()->getTitle()->prepend(__('Tag Type'));

        return $resultPage;
    }

    /**
     * Is the user allowed to view the blog post grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ktpl_Blog::tag_type');
    }


}