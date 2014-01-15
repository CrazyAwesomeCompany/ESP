<?php

namespace CAC\Component\ESP\Adapter\Engine;


use CAC\Component\ESP\Api\Engine\EngineApi;
use CAC\Component\ESP\ESPException;
use CAC\Component\ESP\MailAdapterInterface;

class EngineMail implements MailAdapterInterface
{
    private $api;

    private $options;

    public function __construct(EngineApi $api)
    {
        $this->api = $api;
        $this->options = array(
            'fromName' => 'Crazy Awesome ESP',
            'fromEmail' => 'changeme@crazyawesomecompany.com',
            'replyTo' => null,
        );
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailAdapterInterface::send()
     */
    public function send(array $users, $subject, $body)
    {
        // First create a mailing ID
        $mailingId = $this->api->createMailingFromContent(
            $body,
            $body,
            $subject,
            $this->options['fromName'],
            $this->option['fromEmail'],
            $this->option['replyTo']
        );

        return (bool) $this->api->sendMailing($mailingId, $users);
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailAdapterInterface::sendByTemplate()
     */
    public function sendByTemplate($templateId, array $users, array $params = array())
    {
        throw new ESPException('Not implemented');
    }
}
