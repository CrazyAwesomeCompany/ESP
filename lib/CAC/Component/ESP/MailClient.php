<?php

namespace CAC\Component\ESP;


class MailClient
{
    /**
     * The ESP Adapter
     *
     * @var MailAdapterInterface
     */
    private $adapter;

    public function __construct(MailAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function send(array $users, $subject, $body)
    {
        return $this->adapter->send($users, $subject, $body);
    }

    public function sendByTemplate($templateId, array $users, $subject, $group = 'default')
    {
        return $this->adapter->sendByTemplate($templateId, $users, $subject, array(), $group);
    }
}
