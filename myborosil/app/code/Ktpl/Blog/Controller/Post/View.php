<?php

namespace Ktpl\Blog\Controller\Post;

/**
 * Blog post view
 */
class View extends \Ktpl\Blog\App\Action\Action
{

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
    }

    /**
     * View Blog post action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->moduleEnabled()) {
            return $this->_forwardNoroute();
        }

        $post = $this->_initPost();
        if (!$post) {
            return $this->_forwardNoroute();
        }

        $this->_objectManager->get('\Magento\Framework\Registry')
            ->register('current_blog_post', $post);
        $resultPage = $this->_objectManager->get('Ktpl\Blog\Helper\Page')
            ->prepareResultPage($this, $post);
        return $resultPage;
    }

    /**
     * Init Post
     *
     * @return \Ktpl\Blog\Model\Post || false
     */
    protected function _initPost()
    {
        $id = $this->getRequest()->getParam('id');
        $storeId = $this->_storeManager->getStore()->getId();

        $post = $this->_objectManager->create('Ktpl\Blog\Model\Post')->load($id);

        if (!$post->isVisibleOnStore($storeId)) {
            return false;
        }

        $post->setStoreId($storeId);

        return $post;
    }

}
