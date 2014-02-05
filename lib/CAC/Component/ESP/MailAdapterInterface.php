<?php

namespace CAC\Component\ESP;

interface MailAdapterInterface
{
    /**
     * Send a mail
     *
     * @param array  $users
     * @param string $content
     *
     * @return boolean
     */
    public function send(array $users, $subject, $body);

    /**
     * Send an Email based on a template at the ESP
     *
     * @param integer|string $templateId
     * @param array          $users
     * @param array          $params
     *
     * @return boolean
     */
    public function sendByTemplate($templateId, array $users, $subject = null, $params = array());
}
