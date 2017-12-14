<?php

namespace Ktpl\Blog\Controller\Adminhtml\Import;

/**
 * Blog aw import controller
 */
class Aw extends \Magento\Backend\App\Action
{
    /**
     * Prepare aw import
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->_redirect('*/*/');
    }

    /**
     * Check is allowed access
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ktpl_Blog::import');
    }
}
