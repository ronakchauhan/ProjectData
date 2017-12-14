<?php

namespace Ktpl\HeaderView\Block\Adminhtml\System\Config\Form\Field\TopLinks;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface;

class SortOrder extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @param AbstractElement $element
     * @return string
     * @codeCoverageIgnore
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->getLayout()
            ->createBlock('\Ktpl\HeaderView\Block\Adminhtml\System\Config\Form\Field\TopLinks\SortOrder\Renderer')
            ->setElement($element)
            ->toHtml();
    }
}
