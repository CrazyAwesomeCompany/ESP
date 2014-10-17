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

    /**
     * Get a subscriber from a mailinglist
     *
     * @param string $email
     * @param array $fields
     * @param string $mailinglistId
     */
    public function getSubscriber($email, array $fields = array(), $mailinglistId = null);

}
