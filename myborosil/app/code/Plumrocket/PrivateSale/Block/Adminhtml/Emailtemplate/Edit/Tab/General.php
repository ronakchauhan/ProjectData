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

namespace Plumrocket\PrivateSale\Block\Adminhtml\Emailtemplate\Edit\Tab;

class General extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param \Magento\Store\Model\System\Store       $systemStore
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
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

        $form->setHtmlIdPrefix('privatesale_emailtemplate_');


        $legend = ($model->getId())  ? __('Edit Newsletter Template') : __('Add New Newsletter Template');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => $legend]);

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_authorization->isAllowed('Plumrocket_PrivateSale::privatesale_emailtemplate')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }
        $isElementDisabled = false;

        if ($model->getId()) {
            $fieldset->addField(
                'id',
                'hidden',
                [
                    'name'      => 'id',
                    'value'     => $model->getId() ?: null,
                ]
            );
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
                'disabled' => $isElementDisabled,
                'value' => $model->getData('name'),
            ]
        );

        if (!$this->_storeManager->isSingleStoreMode()) {

            $field = $fieldset->addField(
                'store_id',
                'multiselect',
                [
                    'name' => 'store_id[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, false),
                    'disabled' => $isElementDisabled,
                    'value'     => $model->getStoreId()
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_id',
                'hidden',
                ['name' => 'stores[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }

        $fieldset->addField(
            'date',
            'date',
            [
                'name' => 'date',
                'label' => __('Date'),
                'date_format' => 'yyyy-MM-dd',
                'time_format' => 'HH:mm:ss',
                'title' => __('Date'),
                'required' => true,
                'disabled' => $isElementDisabled,
                'value' => $model->getData('date'),
                'note' => __('The date when newsletter will be sent. Event list below will be loaded based on this date.')
            ]
        );

        $categories = $model->loadCategoriesByCriteria($model->getData('date'), $model->getStoreId());

        $fieldset->addField(
            'categories_ids',
            'multiselect',
            [
                'name' => 'categories_ids',
                'label' => __('Events'),
                'title' => __('Events'),
                'values' => $model->categoriesToOptions($categories),
                // 'required' => true,
                'disabled' => $isElementDisabled,
                'value' => explode(',', $model->getData('categories_ids')),
                'note' => __('Selected events will be included in the newsletter template.')
            ]
        );

        $fieldset = $form->addFieldset('newsletter_fieldset', ['legend' => __('Newsletter')]);

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'title' => __('Title'),
                'note' => __('Newsletter title. Value of variable {{title}}, see below for complete list of variables.'),
                'disabled' => $isElementDisabled,
                'value' => $model->getData('title'),
            ]
        );

        $fieldset->addField(
            'period_text',
            'text',
            [
                'name' => 'period_text',
                'label' => __('Period'),
                'title' => __('Period'),
                'note' => __('Active events date range. Value of variable {{period}}, see below for complete list of variables. Leave blank to automatically generate starting and ending date (period) based on selected Events.'),
                'disabled' => $isElementDisabled,
                'value' => $model->getData('period_text'),
            ]
        );

        $fieldset->addField(
            'template',
            'editor',
            [
                'name' => 'template',
                'label' => __('Full Email Template'),
                'title' => __('Full Email Template'),
                'required' => true,
                'disabled' => $isElementDisabled,
                'value' => $model->getData('template'),
                'after_element_html' => '
                Use variables:<br/>
                <b>{{title}}</b> to insert title into email template;<br/>
                <b>{{period}}</b> to insert period into email template;<br/>
                <b>{{events_list}}</b> to insert list of events into email template;<br/>
                <b>{{start_date}}</b> to insert events starting date into email template;<br/>
                <b>{{end_date}}</b>  to insert events ending date into email template.<br/>
                <br/>
            ',
            ]
        );

        $fieldset = $form->addFieldset('list_fieldset', ['legend' => __('Row of Events List')]);

        $field = $fieldset->addField(
            'list_layout',
            'select',
            [
                'name'      => 'list_layout',
                'label'     => __('Layout'),
                'title'     => __('Layout'),
                'required'  => true,
                'value' => $model->getData('list_layout'),
                'values'    => [
                    1 => __('One Event In Row'),
                    2 => __('Two Events In Row'),
                    3 => __('Three Events In Row'),
                ],
            ]
        );

        $fieldset->addField(
            'list_template',
            'editor',
            [
                'name' => 'list_template',
                'label' => __('Full Email Template'),
                'title' => __('Full Email Template'),
                'required' => true,
                'disabled' => $isElementDisabled,
                'value' => $model->getData('list_template'),
                'after_element_html' => '
                    Use variables:<br/>
                    <b>{{event.name}}</b> to insert event name into row template of events list;<br/>
                    <b>{{event.short_name}}</b> to insert  event short name into row template of events list;<br/>
                    <b>{{event.page_title}}</b> to insert event page title into row template of events list;<br/>
                    <b>{{event.short_page_title}}</b> to insert  event short page title into row template of events list;<br/>
                    <b>{{event.url}}</b> to insert  event url into row template of events list;<br/>
                    <b>{{event.image}}</b> to insert  event image into row template of events list;<br/>
                    <b>{{event.start_date}}</b> to insert  event start date into row template of events list;<br/>
                    <b>{{event.end_date}}</b> to insert  event end date into row template of events list.<br/>
                    You can also use variables <b>{{event2}}</b> and <b>{{event3}}</b> instead of <b>{{event}}</b> if you use "Two Events In Row" or "Three Events In Row" layout. <br />
                    Please use comment tags "<b>'.htmlspecialchars('<!-- event 2 -->').'</b>" and "<b>'.htmlspecialchars('<!-- event 3 -->').'</b>" to determine each event position.',
            ]
        );

        $fieldset->addField(
            'list_template_date_format',
            'text',
            [
                'name' => 'list_template_date_format',
                'label' => __('Date Format'),
                'title' => __('Date Format'),
                // 'required' => true,
                'note' => __('Default value is "m/d/Y" (e.g. '.date('m/d/Y').'). Learn more about <a href="http://php.net/manual/en/function.date.php#refsect1-function.date-parameters" target="_blank" >date formats</a>.'),
                'disabled' => $isElementDisabled,
                'value' => $model->getData('list_template_date_format'),
            ]
        );

        $this->_eventManager->dispatch('plumrocket_privatesale_emailtemplate_edit_tab_main_prepare_form', ['form' => $form]);
        $this->setForm($form);

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
