<?php

namespace Ktpl\Blog\Controller\Index;

/**
 * Blog home page view
 */
class Index extends \Ktpl\Blog\App\Action\Action
{
    /**
     * View blog homepage action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->moduleEnabled()) {
            return $this->_forwardNoroute();
        }

        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

}
