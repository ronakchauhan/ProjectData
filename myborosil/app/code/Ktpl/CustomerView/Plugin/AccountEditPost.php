<?php

namespace Ktpl\CustomerView\Plugin;

class AccountEditPost
{
    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function afterExecute(
        \Magento\Customer\Controller\Account\EditPost $subject,
        $result
        )
    {
        return $result->setPath('customerview/account');
    }
}