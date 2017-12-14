<?php

namespace Ktpl\Blog\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DuplicateButton
 */
class DuplicateButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getPostId()) {
            $data = [
                'label' => __('Duplicate'),
                'class' => 'duplicate',
                'on_click' => 'window.location=\'' . $this->getDuplicateUrl() . '\'',
                'sort_order' => 40,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDuplicateUrl()
    {
        return $this->getUrl('*/*/duplicate', ['id' => $this->getPostId()]);
    }
}
