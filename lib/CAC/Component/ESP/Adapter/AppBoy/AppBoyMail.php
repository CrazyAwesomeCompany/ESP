<?php

namespace CAC\Component\ESP\Adapter\AppBoy;

use CAC\AppBoy\Api\AppBoyApi;
use CAC\Component\ESP\MailAdapterInterface;
use CAC\Component\ESP\ESPException;
use CAC\Component\ESP\Adapter\TemplatingTrait;

class AppBoyMail implements MailAdapterInterface
{
    use TemplatingTrait;

    /**
     * @var AppBoyApi
     */
    private $api;

    /**
     * @var array
     */
    protected $options;


    public function __construct(AppboyApi $api, array $options = [])
    {
        $this->api = $api;
        $this->options = array_replace_recursive(
            [
                'options' => [],
                'templates' => [],
                'globals' => []
            ],
            $options
        );
    }

    /**
     * {@inheritDoc}
     */
    public function send(array $users, $subject, $body) {
        throw new ESPException('AppBoy `send` not implemented');
    }

    /**
     * {@inheritDoc}
     */
    public function sendByTemplate($templateId, array $users, $subject = null, $params = [], $group = 'default', \DateTime $date = null, $fromEmail = null, $replyTo = null) {
        $template = $this->findTemplateByName($templateId, $group);
        $globals = array_merge($this->getOption('globals', $group, []), $params);

        $users = $this->transformUsersToAppboy($users);

        return $this->api->sendTrigger($template['id'], $users, $globals);
    }

    /**
     * {@inheritDoc}
     */
    public function sendByTemplateWithAttachment($templateId, array $user, $subject = null, $params = [], $attachments = [], $group = 'default', \DateTime $date = null, $fromEmail = null, $replyTo = null) {
        throw new ESPException('AppBoy `sendTemplateWithAttachment` not implemented');
    }

    /**
     * Transform the user array to an understandable Appboy user list
     *
     * @param String[] $users
     * @throws ESPException
     * @return String[]
     */
    protected function transformUsersToAppboy(array $users)
    {
        $newList = [];

        foreach ($users as $user) {
            if (empty($user['external_user_id']) && empty($user['id'])) {
                throw new ESPException('Unable to determine AppBoy External User ID');
            }

            $item = [
                'external_user_id' => null,
                'trigger_properties' => [],
            ];

            $item['external_user_id'] = !empty($user['external_user_id']) ? $user['external_user_id'] : $user['id'];
            $item['trigger_properties'] = $user;

            $newList[] = $item;
        }

        return $newList;
    }
}
