<?php

namespace Ktpl\Blog\Controller\Tag;

use \Magento\Store\Model\ScopeInterface;

/**
 * Blog tag posts view
 */
class View extends \Ktpl\Blog\App\Action\Action
{
    /**
     * View blog author action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->moduleEnabled()) {
            return $this->_forwardNoroute();
        }

        $tag = $this->_initTag();
        if (!$tag) {
            return $this->_forwardNoroute();
        }

        $this->_objectManager->get('\Magento\Framework\Registry')->register('current_blog_tag', $tag);

        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

    /**
     * Init author
     *
     * @return \Ktpl\Blog\Model\Tag || false
     */
    protected function _initTag()
    {
        $id = $this->getRequest()->getParam('id');

        $tag = $this->_objectManager->create('Ktpl\Blog\Model\Tag')->load($id);

        if (!$tag->getId()) {
            return false;
        }

        return $tag;
    }

}
