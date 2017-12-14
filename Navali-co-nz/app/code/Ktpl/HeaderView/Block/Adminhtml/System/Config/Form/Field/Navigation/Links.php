<?php

namespace Ktpl\HeaderView\Block\Adminhtml\System\Config\Form\Field\Navigation;

class Links extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    const SIDE_LEFT = 0;
    const SIDE_RIGHT = 1;

    /**
     * @var \Magento\Framework\Data\Form\Element\Factory $elementFactory
     */
    protected $_elementFactory;
    
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        array $data = []
    ) {
        $this->_elementFactory = $elementFactory;
        parent::__construct($context, $data);
    }

    /**
     * to add columns
     *
     * @return void
     */
    protected function _construct()
    {
        $this->addColumn('label', ['label' => __('Label'), 'class' => "input-text required-entry", 'style' => 'width: 140px;']);
        $this->addColumn('url_path', ['label' => __('Url Path'), 'class' => "input-text", 'style' => 'width: 140px;']);
        $this->addColumn('position', ['label' => __('Position'), 'class' => "input-text required-entry validate-number", 'style' => 'width: 60px;']);
        $this->addColumn('side', ['label' => __('Side'), 'style' => 'width: 85px; !important;']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Link');
        parent::_construct();
    }

    /**
     * Render array cell for prototypeJS template
     *
     * @param string $columnName
     * @return string
     * @throws \Exception
     */
    public function renderCellTemplate($columnName)
    {
        if ($columnName == 'side' && isset($this->_columns[$columnName])) 
        {
            $options = [
                self::SIDE_LEFT => __("Left"),
                self::SIDE_RIGHT => __("Right"),
            ];

            $element = $this->_elementFactory->create('select');
            $element->setForm(
                $this->getForm()
            )->setName(
                $this->_getCellInputElementName($columnName)
            )->setHtmlId(
                $this->_getCellInputElementId('<%- _id %>', $columnName)
            )->setValues(
                $options
            );

            if(isset($this->_columns[$columnName]['style']))
                $element->setStyle($this->_columns[$columnName]['style']);
            
            return str_replace("\n", '', $element->getElementHtml());
        }

        return parent::renderCellTemplate($columnName);
    }
}
