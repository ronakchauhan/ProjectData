<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Migs\Logger;
 
use Monolog\Logger as MonoLogger;
 
class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = MonoLogger::DEBUG;
 
    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/pl/migs.log';
}