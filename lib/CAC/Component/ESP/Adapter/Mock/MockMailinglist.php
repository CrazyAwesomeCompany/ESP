<?php

namespace CAC\Component\ESP\Adapter\Mock;

use CAC\Component\ESP\MailinglistAdapterInterface;

class MockMailinglist implements MailinglistAdapterInterface
{
    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailinglistAdapterInterface::subscribe()
     */
    public function subscribe($user)
    {
        return true;
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailinglistAdapterInterface::unsubscribe()
     */
    public function unsubscribe($user) {
        return true;
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailinglistAdapterInterface::getSubscriber()
     */
    public function getSubscriber($email, array $fields = array(), $mailinglistId = null)
    {
        return false;
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
