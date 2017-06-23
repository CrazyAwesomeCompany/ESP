<?php

namespace CAC\Component\ESP\Adapter\AppBoy;

use CAC\Component\ESP\MailinglistAdapterInterface;
use CAC\AppBoy\Api\AppBoyApi;

class AppBoyMailinglist implements MailinglistAdapterInterface
{
    /**
     * @var AppBoyApi
     */
    private $api;

    /**
     * Default AppBoy options
     *
     * @var String[]
     */
    private $options;


    public function __construct(AppBoyApi $api, array $options = [])
    {
        $this->api = $api;
        $this->options = array_replace_recursive(
            [
                'confirmed' => false,
            ],
            $options
        );
    }

    /**
     * {@inheritDoc}
     */
    public function subscribe($user)
    {
        if (isset($user['confirmed'])) {
            $user['email_subscribe'] = $user['confirmed'] ? 'opted_in' : 'subscribed';
            unset($user['confirmed']);
        } else {
            $user['email_subscribe'] = $this->options['confirmed'] ? 'opted_in' : 'subscribed';
        }

        return $this->api->trackUsers([$user]);
    }

    /**
     * {@inheritDoc}
     */
    public function unsubscribe($user)
    {
        $user['email_subscribe'] = 'unsubscribed';

        return $this->api->trackUsers([$user]);
    }

    /**
     * {@inheritDoc}
     */
    public function getUnsubscriptions($options = [])
    {
        return $this->api->getUnsubscriptions();
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscriber($email, array $fields = [], $mailinglistId = null)
    {
        throw new \Exception('Not implemented');
    }
}
