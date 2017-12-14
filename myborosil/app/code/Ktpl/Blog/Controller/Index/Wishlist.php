<?php

namespace Ktpl\Blog\Controller\Index;

use Magento\Framework\App\Action;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;

class Wishlist extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Wishlist\Controller\WishlistProviderInterface
     */
    protected $wishlistFactory;

	/**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * @var Validator
     */
    protected $urlInterface;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Ktpl\Blog\Model\Post\WishlistFactory $wishlistFactory,
        Validator $formKeyValidator,
        \Magento\Customer\Model\Session $customerSession,
        DataPersistorInterface $dataPersistor
    )
    {

        $this->_scopeConfig      = $scopeConfig;
        $this->resultPageFactory = $resultPageFactory;
        $this->wishlistFactory   = $wishlistFactory;
        $this->_customerSession  = $customerSession;
        $this->formKeyValidator  = $formKeyValidator;
        // echo"<pre/>"; print_r(get_class_methods($context));exit;
        $this->url               = $context->getUrl();
        $this->dataPersistor     = $dataPersistor;
        parent::__construct($context);
    }

    public function dispatch(RequestInterface $request)
    {
        if (!$this->_customerSession->authenticate()) {
            $this->_actionFlag->set('', 'no-dispatch', true);
        }

        return parent::dispatch($request);
    }


    /**
     * Loads page content
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $data = [];
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        // if(!$this->_customerSession->isLoggedIn()) {
        //     $resultRedirect->setPath($this->url->getCurrentUrl());
        //     $this->_customerSession->setAfterAuthUrl($this->url->getCurrentUrl());
        //     $this->_customerSession->authenticate();
        //     return true;
        // }

        $requestParams = $this->getRequest()->getParams();
        
        $postId = $requestParams['postid'];
        if (!$postId) {
            $resultRedirect->setPath('*/');
            return $resultRedirect;
        }

        /** @var \Ktpl\Blog\Model\Post\Wishlist $model */
        $model = $this->wishlistFactory->create();
        
        $data['post_id'] = $postId;
        $data['customer_id'] = $this->_customerSession->getCustomer()->getId();
        // $data['update_at'] = $date;

        $model->setData($data);

        try {
                $model->save();
                $this->messageManager->addSuccess(__('Post Saved.'));
                $this->dataPersistor->clear('ktpl_blog_post');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('ideas-blog/category/home');
                }
                return $resultRedirect->setPath('blog/post/wishlist');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                echo"<pre/>"; print_r($e->getMessage());exit;
                $this->messageManager->addException($e, __('Something went wrong while saving the post in wishlist.'));
            }

            $this->dataPersistor->set('ktpl_blog_post', $data);
            return $resultRedirect->setPath('blog/post/wishlist');
    }

}