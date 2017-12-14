<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Additional\Block\Html;

/**
 * Html pager block
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class Pager extends \Magento\Theme\Block\Html\Pager
{
    

    /**
     * The list of available pager limits
     *
     * @var array
     */
    protected $_availableLimit = [3 => 3, 6 => 6, 9 => 9];
}
