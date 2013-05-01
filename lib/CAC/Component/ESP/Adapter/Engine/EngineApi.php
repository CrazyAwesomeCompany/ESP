<?php

namespace CAC\Component\ESP\Adapter\Engine;

use Psr\Log\LoggerInterface;

use Psr\Log\LoggerAwareInterface;

/**
 * E-Ngine API Client
 *
 * Api Client to connect to the E-Ngine ESP webservice.
 *
 * @author Crazy Awesome Company <info@crazyawesomecompany.com>
 *
 * @todo Implement `Mailinglist_getUnsubscriptionsAsCSV`
 * @todo Implement `Subscriber_getByUniqueID`
 * @todo Implement `Subscriber_sendMailingToSubscribers`
 *
 */
class EngineApi implements LoggerAwareInterface
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
        $result = $this->performRequest('Subscriber_set', $user, !$confirmed, $mailinglistId);

        if (!in_array($result, array('OK_UPDATED', 'OK_CONFIRM', 'OK_BEDANKT'))) {
            $e = new EngineApiException(sprintf('User not subscribed to mailinglist. Engine Result: [%s]', $result));
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
     * @param bool    $confirmed     Is the unsubscription already confirmed
     *
     * @return string
     *
     * @throws EngineApiException
     */
    public function unsubscribeUser($email, $mailinglistId, $confirmed = false)
    {
        $result = $this->performRequest('Subscriber_unsubscribe', $email, !$confirmed, $mailinglistId);

        if (!in_array($result, array('OK', 'OK_CONFIRM'))) {
            $e = new EngineApiException(sprintf('User not unsubscribed from mailinglist. Engine Result: [%s]', $result));
            $e->setEngineCode($result);

            throw $e;
        }

        return $result;
    }

    /**
     * Get all mailinglists of the account
     *
     * @return array
     */
    public function getMailinglists()
    {
        $result = $this->performRequest('Mailinglist_all');

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
     * Get Mailinglist Subscriber information
     *
     * @param integer $mailinglistId
     * @param string  $email
     *
     * @return array
     */
    public function getMailinglistUser($mailinglistId, $email)
    {
        $result = $this->performRequest(
            'Subscriber_getByEmail',
            $email,
            array('email', 'firstname', 'infix', 'lastname'),
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
     *
     * @throws EngineApiException Converted SoapFault Exception
     */
    protected function performRequest($method) {
        // Perform the SOAP request
        $args = func_get_args();
        // remove method argument
        array_shift($args);

        try {
            if ($this->logger) {
                $this->logger->debug(sprintf("E-Ngine API call: %s -> %s", $method, json_encode($args)));
            }

            $result = call_user_func_array(array($this->getConnection(), $method), $args);
        } catch (\SoapFault $e) {
            if ($this->logger) {
                $this->logger->error(sprintf("E-Ngine API error: %s", $e->getMessage()));
            }
            // Convert to EngineApiException
            throw new EngineApiException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

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

    /**
     * Set the logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
