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

namespace Plumrocket\PrivateSale\Block\Adminhtml\Splashpage\Tab;

class General extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * Splash page statuses
     * @var \Plumrocket\PrivateSale\Model\Config\Source\Splashpagestatus
     */
    protected $splashpageStatus;

    /**
     * Enable disable
     * @var \Magento\Config\Model\Config\Source\Enabledisable
     */
    protected $enabledisable;

    /**
     * Wsiwyg config
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $wysiwygConfig;

    /**
     * Constuctor
     * @param \Magento\Backend\Block\Template\Context                      $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Framework\Data\FormFactory                          $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config                            $wysiwygConfig
     * @param \Magento\Config\Model\Config\Source\Enabledisable            $enabledisable
     * @param array                                                        $data
     * @param \Plumrocket\PrivateSale\Model\Config\Source\Splashpagestatus $splashpageStatus
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Plumrocket\PrivateSale\Model\Config\Source\Enableddisabled $enabledisable,
        array $data = [],
        \Plumrocket\PrivateSale\Model\Config\Source\Splashpagestatus $splashpageStatus
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->splashpageStatus = $splashpageStatus;
        $this->enabledisable = $enabledisable;
        $this->wysiwygConfig = $wysiwygConfig;
    }


    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {

        $form = $this->_formFactory->create();

        $model = $this->_coreRegistry->registry('current_model');

        $form->setHtmlIdPrefix('privatesale_splashpage_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General')]);

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_authorization->isAllowed('Plumrocket_PrivateSale::privatesale')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }
        $isElementDisabled = false;

        $field = $fieldset->addField(
            'enabled_page',
            'select',
            [
                'name'      => 'enabled_page',
                'label'     => __('Enable Splash Page'),
                'title'     => __('Enable Splash Page'),
                'required'  => true,
                'note' => __('If splash page is disabled, guests will be able to view website pages without restrictions'),
                'values'    => $this->splashpageStatus->toOptionArray(),
            ]
        );

        $fieldset = $form->addFieldset('user_registration', ['legend' => __('User Registration')]);


        $fieldset->addField(
            'enabled_registration',
            'select',
            [
                'name'      => 'enabled_registration',
                'label'     => __('User Registrations'),
                'title'     => __('User Registrations'),
                'type'     => 'boolean',
                'values' => $this->enabledisable->toOptionArray()
            ]
        );


        $field = $fieldset->addField(
            'become_text',
            'editor',
            [
                'name'      => 'become_text',
                'label'     => __('Registration Form Text'),
                'config' => $this->wysiwygConfig->getConfig(),
                'title'     => __('Registration Form Text')
            ]
        );

        $fieldset = $form->addFieldset('lauching_soon', ['legend' => __('Launching Soon')]);

        $fieldset->addField(
            'enabled_launching_soon',
            'select',
            [
                'name'      => 'enabled_launching_soon',
                'label'     => __('Launching Soon'),
                'title'     => __('Launching Soon'),
                'required'  => true,
                'values' => $this->enabledisable->toOptionArray(),
                'note'      => __('If enabled, visitors can only create accounts on splash page. Account login will be disabled.'),
                'disabled'  => $isElementDisabled,
            ]
        );

        $fieldset->addField(
            'launching_text',
            'editor',
            [
                'name' => 'launching_text',
                'label' => __('Launching Soon Registration Confirmation Text'),
                'title' => __('Launching Soon Registration Confirmation Text'),
                'config' => $this->wysiwygConfig->getConfig(),
                'disabled'  => $isElementDisabled
            ]
        );


        /*
          Dependence for field. There in magento bug with Dependence and editor.
          U can uncomment this, when magento fix this bug

          $this->setChild('form_after',
            $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence')
              ->addFieldMap('privatesale_splashpage_enabled_registration', 'enabled_registration')
              ->addFieldMap('privatesale_splashpage_become_text', 'become_text')
              ->addFieldMap('privatesale_splashpage_enabled_launching_soon', 'enabled_launching_soon')
              ->addFieldMap('privatesale_splashpage_launching_text', 'launching_text')
              ->addFieldDependence('become_text', 'enabled_registration', 1)
              ->addFieldDependence('launching_text', 'enabled_launching_soon', 1)
          );*/

        $this->setForm($form);
        $form->setValues($model->getData());

        return parent::_prepareForm();
    }


    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('General Settings');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('General Settings');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
