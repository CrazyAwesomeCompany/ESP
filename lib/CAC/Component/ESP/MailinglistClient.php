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

    public function __construct(MailinglistAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }


    public function subscribe(array $user)
    {
        $result = $this->adapter->subscribe($user);

        return $result;
    }

    public function unsubscribe(array $user)
    {
        $result = $this->adapter->unsubscribe($user);

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
