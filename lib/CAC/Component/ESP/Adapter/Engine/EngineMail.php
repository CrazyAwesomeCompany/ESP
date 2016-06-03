<?php

namespace CAC\Component\ESP\Adapter\Engine;


use CAC\Component\ESP\Api\Engine\EngineApi;
use CAC\Component\ESP\ESPException;
use CAC\Component\ESP\MailAdapterInterface;
use \ForceUTF8\Encoding;

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
        if (isset($options['templates']) && !isset($options['templates']['default'])) {
            // BC-Compatible: add the templates to the default group
            $options['templates']['default'] = $options['templates'];
        }

        $this->api = $api;
        $this->options = array_replace_recursive(
            array(
                'fromName' => 'Crazy Awesome ESP',
                'fromEmail' => 'changeme@crazyawesomecompany.com',
                'replyTo' => null,
                'options' => array(),
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
            Encoding::toLatin1($subject),
            Encoding::toLatin1($this->getOption('fromName')),
            $this->getOption('fromEmail'),
            $this->getOption('replyTo')
        );

        return (bool) $this->api->sendMailing($mailingId, $users);
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailAdapterInterface::sendByTemplate()
     */
    public function sendByTemplate($templateId, array $users, $subject = null, $params = array(), $group = 'default', \DateTime $date = null, $fromEmail = null, $replyTo = null)
    {
        if (!is_numeric($templateId)) {
            $template = $this->findTemplateByName($templateId, $group);
            $templateId = $template['id'];
            $subject = $template['subject'];

            if (isset($template['mailinglist'])) {
                $this->api->selectMailinglist($template['mailinglist']);
            }
        }

        for ($i = 0; $i < count($users); $i++) {
            $users[$i] = array_merge($this->getOption('globals', $group), $users[$i]);
        }

        $mailingId = $this->api->createMailingFromTemplate(
            $templateId,
            Encoding::toLatin1($subject),
            Encoding::toLatin1($this->getOption('fromName', $group)),
            isset($fromEmail) ? $fromEmail : $this->getOption('fromEmail', $group),
            isset($replyTo) ? $replyTo : $this->getOption('replyTo', $group)
        );

        return (bool) $this->api->sendMailing($mailingId, $users, $date, (isset($template['mailinglist']) ? $template['mailinglist'] : null));
    }

    /**
     * (non-PHPdoc)
     * @see \CAC\Component\ESP\MailAdapterInterface::sendByTemplateWithAttachment()
     */
    public function sendByTemplateWithAttachment($templateId, array $user, $subject = null, $params = array(), $group = 'default', $attachments = array(), \DateTime $date = null, $fromEmail = null, $replyTo = null)
    {
        if (!is_numeric($templateId)) {
            $template = $this->findTemplateByName($templateId, $group);
            $templateId = $template['id'];
            $subject = $template['subject'];

            if (isset($template['mailinglist'])) {
                $this->api->selectMailinglist($template['mailinglist']);
            }
        }

        $user = array_merge($this->getOption('globals', $group), $user[0]);

        $mailingId = $this->api->createMailingFromTemplate(
            $templateId,
            Encoding::toLatin1($subject),
            Encoding::toLatin1($this->getOption('fromName', $group)),
            isset($fromEmail) ? $fromEmail : $this->getOption('fromEmail', $group),
            isset($replyTo) ? $replyTo : $this->getOption('replyTo', $group)
        );

        return (bool) $this->api->sendMailingWithAttachment($mailingId, $user, $date, (isset($template['mailinglist']) ? $template['mailinglist'] : null), $attachments);
    }

    /**
     * Find a template by name
     *
     * @param string $name
     * @param string $group
     * @throws ESPException
     */
    private function findTemplateByName($name, $group = 'default')
    {
        if (!array_key_exists($group, $this->options['templates']) || !array_key_exists($name, $this->options['templates'][$group])) {
            throw new ESPException(sprintf("Template configuration for group %s could not be found", $group));
        }

        return $this->options['templates'][$group][$name];
    }

    /**
     * Add a new template
     *
     * @param string $name
     * @param integer $id
     * @param string $subject
     * @param string $group
     */
    public function addTemplate($name, $id, $subject, $group = 'default')
    {
        if (!array_key_exists($group, $this->options['templates'])) {
            $this->options['templates'][$group] = array();
        }

        $this->options['templates'][$group][$name] = array('id' => $id, 'subject' => $subject);
    }

    /**
     * Get a configuration option
     *
     * @param string $name
     * @param string $group
     * @throws ESPException
     * @return string
     */
    protected function getOption($name, $group = 'default')
    {
        // Check if specific group has the option set
        if (array_key_exists($group, $this->options['options']) && isset($this->options['options'][$group][$name])) {
            return $this->options['options'][$group][$name];
        }

        // fallback to the default
        if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }
        print_r($this->options); exit;
        // Invalid option
        throw new ESPException(sprintf("Invalid option requested. No option found [%s:%s]", $group, $name));
    }
}
