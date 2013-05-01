<?php

namespace CAC\Component\ESP\Adapter\Engine;

use CAC\Component\ESP\MailinglistAdapterInterface;

class EngineMailinglist implements MailinglistAdapterInterface
{
    /**
     * @var EngineApi
     */
    private $api;

    private $options = array();

    public function __construct(EngineApi $api)
    {
        $this->api = $api;
        $this->options = array(
            'mailinglist' => 2,
            'confirmed' => false,

        );
    }


    public function subscribe($user)
    {
        if (isset($user['mailinglist'])) {
            $mailinglistId = $user['mailinglist'];
            unset($user['mailinglist']);
        } else {
            $mailinglistId = $this->options['mailinglist'];
        }

        if (isset($user['confirmed'])) {
            $confirmed = $user['confirmed'];
        } else {
            $confirmed = $this->options['confirmed'];
        }

        return $this->api->subscribeUser($user, $mailinglistId, $confirmed);
    }

    public function unsubscribe($user) {
        if (isset($user['mailinglist'])) {
            $mailinglistId = $user['mailinglist'];
            unset($user['mailinglist']);
        } else {
            $mailinglistId = $this->options['mailinglist'];
        }

        if (isset($user['confirmed'])) {
            $confirmed = $user['confirmed'];
        } else {
            $confirmed = $this->options['confirmed'];
        }

        if (!isset($user['email'])) {
            // @todo throw exception

            return false;
        }

        return $this->api->unsubscribeUser($user['email'], $mailinglistId, $confirmed);
    }

    public function getUnsubscriptions($options = array()) {
        // TODO: Auto-generated method stub
        $from = new \DateTime();
        $from->sub(new \DateInterval('P1D'));

        return $this->api->getMailinglistUnsubscriptions($this->options['mailinglist'], $from);
    }

}
