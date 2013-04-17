<?php

namespace CAC\Component\ESP;

interface MailinglistAdapterInterface
{

    /**
     * Subscribe a user to a mailinglist
     *
     * @param array $user
     */
    public function subscribe($user);

    /**
     * Unsubscribe a User from a mailinglist
     *
     * @param array $user
     */
    public function unsubscribe($user);

    /**
     * Get unsubscriptions from a mailinglist
     *
     * @param array $options
     */
    public function getUnsubscriptions($options = array());


}
