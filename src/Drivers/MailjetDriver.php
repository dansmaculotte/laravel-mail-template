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
    public $body = [];

    /** @var array */
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

        $this->client = new Client($config['key'], $config['secret'], true, [
            'version' => 'v3.1',
        ]);
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
        $this->message['TemplateID'] = intval($template);
        $this->message['TemplateLanguage'] = true;

        return $this;
    }

    /**
     * @param string $subjet
     * @return Driver
     */
    public function setSubject(string $subjet): Driver
    {
        $this->message['Subject'] = $subjet;

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
     * @param string $file
     * @param string $name
     * @return Driver
     */
    public function addAttachment(string $file, string $name): Driver
    {
        $this->message['Attachments'][] = [
            'Filename' => $name,
            'ContentType' => mime_content_type($file),
            'Base64Content' => base64_encode(file_get_contents($file)),
        ];

        return $this;
    }

    /**
     * @param bool $enable
     * @return Driver
     */
    public function trackClicks(bool $enable): Driver
    {
        return $this;
    }

    /**
     * @param bool $enable
     * @return Driver
     */
    public function trackOpens(bool $enable): Driver
    {
        return $this;
    }

    /**
     * @return array
     * @throws SendError
     */
    public function send(): array
    {
        $response = $this->client->post(Resources::$Email, [
            'body' => array_merge($this->body, [
                'Messages' => [
                    $this->message,
                ],
            ])
        ]);

        if ($response->success() == false) {
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
            'body' => array_merge($this->body, [
                'Messages' => [
                    $this->message,
                ],
            ])
        ];
    }
}
