<?php

namespace CAC\Component\ESP\Adapter\Mock;

use CAC\Component\ESP\MailinglistAdapterInterface;

class MockMailinglist implements MailinglistAdapterInterface
{
    public function subscribe($user)
    {
        return true;
    }

    public function unsubscribe($user) {
        return true;
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailinglistAdapterInterface::getUnsubscriptions()
     */
    public function getUnsubscriptions($options = array())
    {
        return array();
    }
}
