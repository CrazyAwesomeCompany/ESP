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
        $this->options = array('mailinglist' => 2);
    }


    public function subscribe($user)
    {
        if (isset($user['mailinglist'])) {
            $mailinglistId = $user['mailinglist'];
            unset($user['mailinglist']);
        } else {
            $mailinglistId = $this->options['mailinglist'];
        }

        return $this->api->subscribeUser($user, $mailinglistId);
    }

    public function unsubscribe($user) {
        if (isset($user['mailinglist'])) {
            $mailinglistId = $user['mailinglist'];
            unset($user['mailinglist']);
        } else {
            $mailinglistId = $this->options['mailinglist'];
        }

        if (!isset($user['email'])) {
            // @todo throw exception

            return false;
        }

        return $this->api->unsubscribeUser($user['email'], $mailinglistId);
    }

    public function getUnsubscriptions($options) {
        // TODO: Auto-generated method stub
        $from = new \DateTime();
        $from->sub(new \DateInterval('P1D'));

        return $this->api->getMailinglistUnsubscriptions($this->options['mailinglist'], $from);
    }

}
