<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Migs\Block\Info;

use Magento\Framework\View\Element\Template;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
class Hosted extends \Magento\Payment\Block\Info
{
    protected $_template = 'PL_Migs::info/hosted.phtml';

    /**
     * @var \PL\Migs\Helper\Data
     */
    protected $migsHelper;

    /**
     * @param \PL\Migs\Helper\Data $migsHelper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \PL\Migs\Helper\Data $migsHelper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->migsHelper = $migsHelper;
    }
}
