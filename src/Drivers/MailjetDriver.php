<?php

namespace DansMaCulotte\MailTemplate\Drivers;

use DansMaCulotte\MailTemplate\Exceptions\InvalidConfiguration;
use DansMaCulotte\MailTemplate\Exceptions\SendError;
use Mailjet\Client;
use Mailjet\Resources;

/**
 * Class MailjetDriver
 * @package DansMaCulotte\MailTemplate\Drivers
 */
class MailjetDriver implements Driver
{
    /** @var Client|null  */
    public $client = null;

    /** @var array */
    private $debugEmail = null;

    public $message = [];

    /**
     * MailjetDriver constructor.
     * @param $config
     * @throws InvalidConfiguration
     */
    public function __construct($config)
    {
        if (!isset($config['key'])) {
            throw InvalidConfiguration::invalidCredential('mailjet', 'key');
        }

        if (!isset($config['secret'])) {
            throw InvalidConfiguration::invalidCredential('mailjet', 'secret');
        }

        if (isset($config['debug_email'])) {
            $this->debugEmail = $config['debug_email'];
            if (!(isset($this->debugEmail['Name']) && isset($this->debugEmail['Email']))) {
                throw new InvalidConfiguration('debug_email in mailjet configuration must have "Name" and "Email" keys');
            }
        }

        $this->client = new Client($config['key'], $config['secret'], true, [
            'version' => 'v3.1',
        ]);
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
        $this->message['From']['Name'] = $name;
        $this->message['From']['Email'] = $email;

        return $this;
    }

    /**
     * @param string $template
     * @return Driver
     */
    public function setTemplate($template): Driver
    {
        $this->message['TemplateID'] = (int) $template;
        $this->message['TemplateLanguage'] = true;

        return $this;
    }

    /**
     * @param string $subject
     * @return Driver
     */
    public function setSubject(string $subject): Driver
    {
        $this->message['Subject'] = $subject;

        return $this;
    }

    /**
     * @param string $name
     * @param string $email
     * @return Driver
     */
    public function setRecipient(string $name, string $email): Driver
    {
        $this->message['To'][] = [
            'Name' => $name,
            'Email' => $email,
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
        $this->message['Bcc'][] = [
            'Name' => $name,
            'Email' => $email,
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
            $this->message['Variables'][$variableKey] = $variableValue;
        }

        return $this;
    }

    /**
     * @param string $language
     * @return Driver
     */
    public function setLanguage(string $language): Driver
    {
        $this->message['Variables']['language'] = $language;

        return $this;
    }

    /**
     * @return array
     * @throws SendError
     */
    public function send(): array
    {
        if ($this->debugEmail) {
            $this->message['TemplateErrorReporting'] = $this->debugEmail;
        }

        $response = $this->client->post(Resources::$Email, [
            'body' => [
                'Messages' => [
                    $this->message,
                ],
            ],
        ]);

        if ($response->success() === false) {
            throw SendError::responseError('mailjet');
        }

        return $response->getData();
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
