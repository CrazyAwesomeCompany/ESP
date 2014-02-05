<?php

namespace CAC\Component\ESP\Adapter\Engine;


use CAC\Component\ESP\Api\Engine\EngineApi;
use CAC\Component\ESP\ESPException;
use CAC\Component\ESP\MailAdapterInterface;

class EngineMail implements MailAdapterInterface
{
    /**
     * @var EngineApi
     */
    private $api;

    /**
     * @var array
     */
    private $options;

    public function __construct(EngineApi $api, array $options = array())
    {
        $this->api = $api;
        $this->options = array_replace_recursive(
            array(
                'fromName' => 'Crazy Awesome ESP',
                'fromEmail' => 'changeme@crazyawesomecompany.com',
                'replyTo' => null,
                'templates' => array(),
                'globals' => array()
            ),
            $options
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
            $this->options['fromEmail'],
            $this->options['replyTo']
        );

        return (bool) $this->api->sendMailing($mailingId, $users);
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailAdapterInterface::sendByTemplate()
     */
    public function sendByTemplate($templateId, array $users, $subject = null, $params = array())
    {
        if (!is_numeric($templateId)) {
            $template = $this->findTemplateByName($templateId);
            $templateId = $template['id'];
            $subject = $template['subject'];
        }

        for ($i = 0; $i < count($users); $i++) {
            $users[$i] = array_merge($this->options['globals'], $users[$i]);
        }

        $mailingId = $this->api->createMailingFromTemplate(
            $templateId,
            $subject,
            $this->options['fromName'],
            $this->options['fromEmail'],
            $this->options['replyTo']
        );

        return (bool) $this->api->sendMailing($mailingId, $users);
    }

    /**
     * Find a template by name
     *
     * @param string $name
     * @throws ESPException
     */
    private function findTemplateByName($name)
    {
        if (!array_key_exists($name, $this->options['templates'])) {
            throw new ESPException("Template configuration could not be found");
        }

        return $this->options['templates'][$name];
    }
}
