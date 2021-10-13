<?php

namespace DansMaCulotte\MailTemplate\Drivers;

use DansMaCulotte\MailTemplate\Exceptions\InvalidConfiguration;
use DansMaCulotte\MailTemplate\Exceptions\SendError;
use Mailgun\Exception\HttpClientException;
use Mailgun\Mailgun;

class MailgunDriver implements Driver
{

    /** @var Mailgun|null  */
    public $client = null;

    /** @var array */
    public $message = [];

    /** @var string */
    public $domain;

    /**
     * Driver constructor.
     * @param array $config
     * @throws InvalidConfiguration
     */
    public function __construct(array $config)
    {
        if (!isset($config['key'])) {
            throw InvalidConfiguration::invalidCredential('mailgun', 'key');
        }

        if (!isset($config['domain'])) {
            throw InvalidConfiguration::invalidCredential('mailgun', 'domain');
        }

        $this->client = Mailgun::create($config['key']);
        $this->domain = $config['domain'];
    }

    /**
     * @return Driver
     */
    public function make(): Driver
    {
        $this->message = [];

        return $this;
    }

    /**
     * @param string $name
     * @param string $email
     * @return Driver
     */
    public function setFrom(string $name, string $email): Driver
    {
        $this->message['from'] = "${name} <${email}>";

        return $this;
    }

    /**
     * @param string $template
     * @return Driver
     */
    public function setTemplate($template): Driver
    {
        $this->message['template'] = $template;

        return $this;
    }

    /**
     * @param string $subject
     * @return Driver
     */
    public function setSubject(string $subject): Driver
    {
        $this->message['subject'] = $subject;

        return $this;
    }

    /**
     * @param string $name
     * @param string $email
     * @return Driver
     */
    public function setRecipient(string $name, string $email): Driver
    {
        $this->message['to'] = "${name} <${email}>";

        return $this;
    }

    /**
     * @param string $name
     * @param string $email
     * @return Driver
     */
    public function setBcc(string $name, string $email): Driver
    {
        $this->message['bcc'] = "${name} <${email}>";

        return $this;
    }

    /**
     * @param array $variables
     * @return Driver
     */
    public function setVariables(array $variables): Driver
    {
        $this->message['h:X-Mailgun-Variables'] = json_encode($variables);

        return $this;
    }

    /**
     * @param string $language
     * @return Driver
     */
    public function setLanguage(string $language): Driver
    {
        return $this;
    }

    /**
     * @return array
     * @throws SendError
     */
    public function send(): array
    {
        try {
            $response = $this->client->messages()->send(
                $this->domain,
                $this->message
            );

            return [
                'id' => $response->getId(),
                'message' => $response->getMessage(),
            ];
        } catch (HttpClientException $exception) {
            throw SendError::responseError('mailgun', 0, $exception);
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'body' => [
                'Messages' => [
                    $this->message,
                ],
            ],
        ];
    }
}
