<?php

namespace CAC\Component\ESP\Adapter\Engine;

use CAC\Component\ESP\MailinglistAdapterInterface;
use CAC\Component\ESP\Api\Engine\EngineApi;

class EngineMailinglist implements MailinglistAdapterInterface
{
    /**
     * @var EngineApi
     */
    private $api;

    private $options = array();

    /**
     * @param EngineApi $api
     * @param array $options
     */
    public function __construct(EngineApi $api, $options = array())
    {
        $this->api = $api;
        $this->options = array_replace_recursive(
            array(
                'mailinglist' => null,
                'confirmed' => false,
            ),
            $options
        );
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailinglistAdapterInterface::subscribe()
     */
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

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailinglistAdapterInterface::unsubscribe()
     */
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

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailinglistAdapterInterface::getSubscriber()
     */
    public function getSubscriber($email, array $fields = array(), $mailinglistId = null)
    {
        if (null == $mailinglistId) {
            $mailinglistId = $this->options['mailinglist'];
        }

        return $this->api->getMailinglistUser($mailinglistId, $email, $fields);
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailinglistAdapterInterface::getUnsubscriptions()
     */
    public function getUnsubscriptions($options = array()) {
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
