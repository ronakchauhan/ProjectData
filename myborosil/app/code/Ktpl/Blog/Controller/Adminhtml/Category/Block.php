<?php

namespace Ktpl\Blog\Controller\Adminhtml\Category;

class Block extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Ktpl_Blog::ajax_category_block';

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Cms\Model\BlockFactory $blockFactory
     */
    protected $_blockFactory;

    /**
     * @var \Ktpl\SectionView\Model\SectionFactory $sectionFactory
     */
    protected $_sectionFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Ktpl\SectionView\Model\SectionFactory $sectionFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_blockFactory = $blockFactory;
        $this->_sectionFactory = $sectionFactory;
        parent::__construct($context);
    }

    /**
     * block action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $resultData = [['value' => '0', 'label' => __('[ SELECT BLOCK ]')]];

        if($this->getRequest()->isAjax())
        {
            $type = $this->getRequest()->getParam('type');
            if($type != \Ktpl\PageView\Block\Adminhtml\Options::TYPE_NONE)
            {
                switch($type)
                {
                    case \Ktpl\PageView\Block\Adminhtml\Options::TYPE_SECTION:
                        $sectionObject = $this->_sectionFactory->create();
                        foreach($sectionObject->getCollection() as $section)
                            $resultData[] = ['value' => $section->getId(), 'label' => $section->getTitle()];
                        break;
                    case \Ktpl\PageView\Block\Adminhtml\Options::TYPE_BLOCK: 
                        $blockObject = $this->_blockFactory->create();
                        foreach($blockObject->getCollection() as $block)
                            $resultData[] = ['value' => $block->getId(), 'label' => $block->getTitle()];
                        break;
                }
            }
        }
        return $result->setData($resultData);
    }
}
