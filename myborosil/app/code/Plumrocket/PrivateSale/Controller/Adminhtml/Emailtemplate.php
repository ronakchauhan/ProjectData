<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket Private Sales and Flash Sales v4.x.x
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\PrivateSale\Controller\Adminhtml;

class Emailtemplate extends \Plumrocket\Base\Controller\Adminhtml\Actions
{
    const ADMIN_RESOURCE = 'Plumrocket_PrivateSale::privatesale';

    /**
     * Form session key
     * @var string
     */
    protected $_formSessionKey  = 'privatesale_emailtemplate_form_data';

    /**
     * Model of main class
     * @var string
     */
    protected $_modelClass      = 'Plumrocket\PrivateSale\Model\Emailtemplate';

    /**
     * Actibe menu
     * @var string
     */
    protected $_activeMenu     = 'Plumrocket_PrivateSale::emailtemplate';

    /**
     * Object Title
     * @var string
     */
    protected $_objectTitle     = 'Email Template';

    /**
     * Object titles
     * @var string
     */
    protected $_objectTitles    = 'Email Templates';

    /**
     * Status field
     * @var string
     */
    protected $_statusField     = 'status';

    /**
     * Emailtemplate Factory
     * @var \Plumrocket\PrivateSale\Model\EmailtemplateFactory
     */
    protected $emailtemplateFactory;

    /**
     * Emailtemplate constructor.
     *
     * @param \Magento\Backend\App\Action\Context                $context
     * @param \Plumrocket\PrivateSale\Model\EmailtemplateFactory $emailtemplateFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Plumrocket\PrivateSale\Model\EmailtemplateFactory $emailtemplateFactory
    ) {
        $this->emailtemplateFactory = $emailtemplateFactory;
        parent::__construct($context);
    }

    /**
     * Init action for email template
     * @return void
     */
    protected function _initTemplateAction($template)
    {
        $_request = $this->getRequest();

        if ($_request->getParam('id')) {
            $_model = $this->emailtemplateFactory->create()->load($_request->getParam('id'));
            $this->_getRegistry()->register('current_model', $_model);

            $this->getResponse()->setBody(
                $this->_view->getLayout()->createBlock('Plumrocket\PrivateSale\Block\Adminhtml\Emailtemplate\Generator')->setTemplate($template)->toHtml()
            );
            return $this;
        }

        $this->_forward('no-route');
    }

}
