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

    public function sendByTemplate($templateId, array $users, $subject = null, $group = 'default', \DateTime $date = null, $fromEmail = null, $replyTo = null)
    {
        return $this->adapter->sendByTemplate($templateId, $users, $subject, array(), $group, $date, $fromEmail, $replyTo);
    }

    public function sendByTemplateWithAttachment($templateId, array $user, $subject = null, $group = 'default', $attachments = array(), \DateTime $date = null, $fromEmail = null, $replyTo = null)
    {
        return $this->adapter->sendByTemplateWithAttachment($templateId, $user, $subject, array(), $group, $attachments, $date, $fromEmail, $replyTo);
    }
}
