<?php

namespace Ktpl\Testimonial\Controller\Adminhtml;

/**
 * Items controller
 */
abstract class Testimonial extends \Magento\Backend\App\Action
{
    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ktpl_Testimonial::manage_testimonials');
    }
}
