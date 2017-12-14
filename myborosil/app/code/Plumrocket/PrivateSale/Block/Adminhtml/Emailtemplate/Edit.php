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

namespace Plumrocket\PrivateSale\Block\Adminhtml\Emailtemplate;


class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * {@inheritdoc}
     */
    protected $formId = 'edit_form';

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize cms page edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Plumrocket_PrivateSale';
        $this->_controller = 'adminhtml_emailtemplate';

        parent::_construct();


        if ($this->_isAllowedAction('Plumrocket_PrivateSale::privatesale')) {

            $model = $this->_getModel();
            if ($model->getId()) {
                $this->buttonList->add(
                    'preview',
                    [
                        'label' => __('Preview'),
                        'onclick' => 'window.open(\'' . $this->_getPreviewUrl() . '\'); return false;',
                        'data_attribute' => [
                            'mage-init' => [
                                'button' => ['event' => 'preview1', 'target' => '#edit_form'],
                            ],
                        ]
                    ],
                    -100
                );

                $this->buttonList->add(
                    'generate',
                    [
                        'label' => __('Generate HTML'),
                        'onclick' => 'window.open(\'' . $this->_getGenerateUrl() . '\')',
                        'data_attribute' => [
                            'mage-init' => [
                                'button' => ['event' => 'generate', 'target' => '#edit_form'],
                            ],
                        ]
                    ],
                    -100
                );
            }

            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }

    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($model->getId()) {
            return __("Edit Affiliate");
        } else {
            return __('New Affiliate');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Retrieve model
     * @return Plumrocket\PrivateSale\Model|EmailTemplate
     */
    protected function _getModel()
    {
        return $this->_coreRegistry->registry('current_model');
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }

    /**
     * Retrieve preview url
     * @return string
     */
    protected function _getPreviewUrl()
    {
        return $this->getUrl('*/*/preview', ['_current' => true]);
    }

    /**
     * Retrieve generate url
     * @return string
     */
    protected function _getGenerateUrl()
    {
        return $this->getUrl('*/*/generate', ['_current' => true]);
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $html = parent::_toHtml();
        return $html . $this->getJs();
    }

    protected function getJs()
    {
        return "<script type='text/javascript'>
            require(['jquery', 'mage/mage', 'domReady!'], function($){
                $('#".$this->formId."').mage('emailtemplateEdit', " . $this->getJsOptions() . ");
            });
        </script>";
    }

    /**
     * Retrieve options for js script
     * @return string
     */
    protected function getJsOptions()
    {

        $params = [
            'updateCategoryUrl' => $this->getUrl('prprivatesale/emailtemplate/category')
            ];

        return json_encode($params);
    }
}
