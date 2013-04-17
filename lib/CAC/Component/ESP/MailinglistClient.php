<?php

namespace CAC\Component\ESP;

use CAC\Component\ESP\MailinglistAdapterInterface;

class MailinglistClient
{
    /**
     * The ESP Adapter
     *
     * @var MailinglistAdapterInterface
     */
    private $adapter;

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
    }





}
