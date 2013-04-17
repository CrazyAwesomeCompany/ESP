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

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailinglistAdapterInterface::getUnsubscriptions()
     */
    public function getUnsubscriptions($options) {
        $options = array_replace_recursive(
            array(
                'mailinglist' => '',
                'from' => null,
                'till' => null
            ),
            $this->options,
            $options
        );

        if (!($options['from'] instanceof \DateTime)) {
            $options['from'] = new \DateTime($options['from']);
        }

        if (!($options['till'] instanceof \DateTime)) {
            $options['till'] = new \DateTime($options['till']);
        }

        return $this->api->getMailinglistUnsubscriptions($options['mailinglist'], $options['from'], $options['till']);
    }

}
