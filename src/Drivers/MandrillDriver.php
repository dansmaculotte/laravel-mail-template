<?php

namespace DansMaCulotte\MailTemplate\Drivers;

use DansMaCulotte\MailTemplate\Exceptions\InvalidConfiguration;
use DansMaCulotte\MailTemplate\Exceptions\SendError;
use Mandrill;
use Mandrill_Error;

/**
 * Class MandrillDriver
 * @package DansMaCulotte\MailTemplate\Drivers
 */
class MandrillDriver implements Driver
{
    /** @var Mandrill|null  */
    public $client = null;

    /** @var array */
    public $body = [];

    /** @var array */
    public $message = [];

    /**
     * MandrillDriver constructor.
     * @param $config
     * @throws InvalidConfiguration
     * @throws Mandrill_Error
     */
    public function __construct($config)
    {
        if (!isset($config['secret'])) {
            throw InvalidConfiguration::invalidCredential('mandrill', 'secret');
        }

        $this->client = new Mandrill($config['secret']);
    }

    /**
     * @return Driver
     */
    public function make(): Driver
    {
        $this->body = [];
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
        $this->message['from_name'] = $name;
        $this->message['from_email'] = $email;

        return $this;
    }

    /**
     * @param string $template
     * @return Driver
     */
    public function setTemplate($template): Driver
    {
        $this->body['template'] = $template;

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
        $this->message['to'][] = [
            'name' => $name,
            'email' => $email,
            'type' => 'to',
        ];

        return $this;
    }

    /**
     * @param string $name
     * @param string $email
     * @return Driver
     */
    public function setBcc(string $name, string $email): Driver
    {
        $this->message['bcc'][] = [
            'name' => $name,
            'email' => $email,
            'type' => 'bcc',
        ];

        return $this;
    }

    /**
     * @param array $variables
     * @return Driver
     */
    public function setVariables(array $variables): Driver
    {
        foreach ($variables as $variableKey => $variableValue) {
            $this->message['global_merge_vars'][] = [
                'name' => strtoupper($variableKey),
                'content' => $variableValue,
            ];
        }

        return $this;
    }

    /**
     * @param string $language
     * @return Driver
     */
    public function setLanguage(string $language): Driver
    {
        $this->message['global_merge_vars'][] = [
            'name' => 'MC_LANGUAGE',
            'content' => $language,
        ];

        return $this;
    }


    /**
     * @return array
     * @throws SendError
     */
    public function send(): array
    {
        $response = [];

        try {
            $response = $this->client->messages->sendTemplate(
                $this->body['template'],
                [],
                $this->message
            );
        } catch (Mandrill_Error $exception) {
            throw SendError::responseError('mandrill', 0, $exception);
        }

        return $response;
    }

    public function toArray(): array
    {
        return [
            'body' => [
                'message' => $this->message,
            ],
        ];
    }
}
