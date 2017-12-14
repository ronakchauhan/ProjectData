<?php

namespace Ktpl\Tag\Controller\Adminhtml\Tag\Type;

class Delete extends \Ktpl\Blog\Controller\Adminhtml\Tag\Type
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Ktpl_Blog::tag_type';

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('tag_type_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $title = "";
            try {
                // init model and delete
                $model = $this->_objectManager->create('Ktpl\Blog\Model\Tag\Type');
                $model->load($id);
                $title = $model->getTitle();
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('The tag type has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['tag_type_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a tag type to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
