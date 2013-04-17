<?php

namespace CAC\Component\ESP\Adapter\Engine;

/**
 * E-Ngine API Client
 *
 * Api Client to connect to the E-Ngine ESP webservice.
 *
 * @author Crazy Awesome Company <info@crazyawesomecompany.com>
 *
 * @todo Implement `Mailinglist_getUnsubscriptionsAsCSV`
 * @todo Implement `Subscriber_getByEmail`
 * @todo Implement `Subscriber_getByUniqueID`
 * @todo Implement `Subscriber_sendMailingToSubscribers`
 *
 */
class EngineApi
{
    /**
     * Api connection
     *
     * @var \SoapClient
     */
    private $connection;

    /**
     * Api configuration
     *
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = array_replace_recursive(
            array(
                "domain" => "",
                "path" => "/soap/server.live.php",
                "customer" => "",
                "user" => "",
                "password" => "",
                "trace" => false
            ),
            $config
        );
    }

    /**
     * Subscribe a User to a Mailinglist
     *
     * @param array   $user          The user data
     * @param integer $mailinglistId The mailinglist id to subscribe the user
     * @param bool    $confirmed     Is the user already confirmed
     *
     * @return string
     *
     * @throws EngineApiException
     */
    public function subscribeUser(array $user, $mailinglistId, $confirmed = false)
    {
        $result = $this->performRequest('Subscriber_set', $user, $confirmed, $mailinglistId);

        if (!in_array($result, array('OK_UPDATED', 'OK_CONFIRMED', 'OK_BEDANKT'))) {
            $e = new EngineApiException('User not subscribed to mailinglist');
            $e->setEngineCode($result);

            throw $e;
        }

        return $result;
    }

    /**
     * Unsubscribe a User from a Mailinglist
     *
     * @param string  $email         The emailaddress to unsubscribe
     * @param integer $mailinglistId The mailinglist id to unsubscribe the user from
     * @param bool    $confirm       Send a confirmation mail to unsubscribe
     *
     * @return string
     *
     * @throws EngineApiException
     */
    public function unsubscribeUser($email, $mailinglistId, $confirm = true)
    {
        $result = $this->performRequest('Subscriber_unsubscribe', $email, $confirm, $mailinglistId);

        if (!in_array($result, array('OK', 'OK_CONFIRM'))) {
            $e = new EngineApiException('User not unsubscribed from mailinglist');
            $e->setEngineCode($result);

            throw $e;
        }

        return $result;
    }

    /**
     * Get all unsubscriptions from a mailingslist of a specific time period
     *
     * @param integer   $mailinglistId
     * @param \DateTime $from
     * @param \DateTime $till
     *
     * @return array
     */
    public function getMailinglistUnsubscriptions($mailinglistId, \DateTime $from, \DateTime $till = null)
    {
        if (null === $till) {
            // till now if no till is given
            $till = new \DateTime();
        }

        $result = $this->performRequest(
            'Mailinglist_getUnsubscriptions',
            $from->format('Y-m-d H:i:s'),
            $till->format('Y-m-d H:i:s'),
            null,
            array('self', 'admin', 'hard', 'soft', 'spam', 'zombie'),
            $mailinglistId
        );

        return $result;
    }

    /**
     * Perform the SOAP request against the E-Ngine webservice
     *
     * @param string $method The method to call
     * @param mixed  ...     Additional parameters
     *
     * @return mixed
     */
    protected function performRequest($method) {
        // TODO Perform the SOAP request
        $args = func_get_args();
        // remove method argument
        array_shift($args);

        $result = call_user_func_array(array($this->getConnection(), $method), $args);

        return $result;
    }

    /**
     * Get the SOAP connection
     *
     * @return SoapClient The SoapClient connection
     */
    protected function getConnection()
    {
        if ($this->connection === null) {
            // create a connection
            $connection = new \SoapClient(
                null,
                array(
                    "location" => "http://" . $this->config["domain"] . $this->config["path"],
                    "uri" => "http://" . $this->config["domain"] . $this->config["path"],
                    "login" => $this->config["customer"] . "__" . $this->config["user"],
                    "password" => $this->config["password"],
                    "trace" => $this->config["trace"]
                )
            );

            $this->connection = $connection;
        }

        return $this->connection;
    }
}