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
     * @param DateTime       $date
     * $param string|null    $fromEmail
     * $param string|null    $replyEmail
     *
     * @return boolean
     */
    public function sendByTemplate($templateId, array $users, $subject = null, $params = array(), $group = 'default', \DateTime $date = null, $fromEmail = null, $replyTo = null);

    /**
     * Send an Email based on a template at the ESP
     *
     * @param integer|string $templateId
     * @param array          $user
     * @param array          $params
     * @param array          $attachments
     * @param DateTime       $date
     * $param string|null    $fromEmail
     * $param string|null    $replyEmail
     *
     * @return boolean
     */
    public function sendByTemplateWithAttachment($templateId, array $user, $subject = null, $params = array(), $attachments = array(), $group = 'default', \DateTime $date = null, $fromEmail = null, $replyTo = null);
}
