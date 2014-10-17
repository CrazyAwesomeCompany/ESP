<?php

namespace CAC\Component\ESP;

use Psr\Log\LoggerInterface;

use Psr\Log\setLogger;

use Psr\Log\LoggerAwareInterface;

use CAC\Component\ESP\MailinglistAdapterInterface;

class MailinglistClient implements LoggerAwareInterface
{
    /**
     * The ESP Adapter
     *
     * @var MailinglistAdapterInterface
     */
    private $adapter;

    /**
     * @var LoggerInterface
     */
    private $logger;


    /**
     * @param MailinglistAdapterInterface $adapter
     */
    public function __construct(MailinglistAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Subscribe a User
     *
     * @param array $user
     * @return mixed
     */
    public function subscribe(array $user)
    {
        $result = $this->adapter->subscribe($user);

        return $result;
    }

    /**
     * Unsubscribe a User
     *
     * @param array $user
     * @return mixed
     */
    public function unsubscribe(array $user)
    {
        $result = $this->adapter->unsubscribe($user);

        return $result;
    }

    /**
     * Get a Subscriber from the Mailinglist
     *
     * @param string $email
     * @param array  $fields
     * @param string $mailinglistId
     *
     * @return array
     */
    public function getSubscriber($email, array $fields = array(), $mailinglistId = null)
    {
        $result = $this->adapter->getSubscriber($email, $fields, $mailinglistId);

        return $result;
    }

    /**
     * (non-PHPdoc)
     * @see \Psr\Log\LoggerAwareInterface::setLogger()
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
