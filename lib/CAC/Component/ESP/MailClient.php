<?php

namespace CAC\Component\ESP;


class MailClient
{
    /**
     * The ESP Adapter
     *
     * @var MailInterface
     */
    private $adapter;

    public function __construct(MailAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }




}
