<?php

namespace CAC\Component\ESP\Adapter\Mock;

use CAC\Component\ESP\MailAdapterInterface;

class MockMail implements MailAdapterInterface
{
    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailAdapterInterface::send()
     */
    public function send(array $users, $subject, $body)
    {
        return true;
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailAdapterInterface::sendByTemplate()
     */
    public function sendByTemplate($templateId, array $users, $subject = null, $params = array(), $group = 'default', \DateTime $date = null, $fromEmail = null, $replyTo = null)
    {
        return true;
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailAdapterInterface::sendByTemplateWithAttachment()
     */
    public function sendByTemplateWithAttachment($templateId, array $user, $subject = null, $params = array(), $attachments = array(), $group = 'default', \DateTime $date = null, $fromEmail = null, $replyTo = null)
    {
        return true;
    }
}
