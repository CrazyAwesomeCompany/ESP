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
    public function sendByTemplate($templateId, array $users, $subject = null, $params = array())
    {
        return true;
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailAdapterInterface::sendByTemplateWithAttachment()
     */
    public function sendByTemplateWithAttachment($templateId, array $user, $subject = null, $params = array(), $attachments = array())
    {
        return true;
    }
}
