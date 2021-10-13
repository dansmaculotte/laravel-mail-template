<?php

namespace DansMaCulotte\MailTemplate\Drivers;

use DansMaCulotte\MailTemplate\Exceptions\InvalidConfiguration;
use DansMaCulotte\MailTemplate\Exceptions\SendError;
use GuzzleHttp\Client;
use SendinBlue\Client\Api\SMTPApi;
use SendinBlue\Client\ApiException;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Model\SendSmtpEmailBcc;
use SendinBlue\Client\Model\SendSmtpEmailSender;
use SendinBlue\Client\Model\SendSmtpEmailTo;

class SendinblueDriver implements Driver
{

    /** @var SMTPApi|null  */
    public $client = null;

    /** @var array */
    public $message = [];

    /**
     * Driver constructor.
     * @param array $config
     * @throws InvalidConfiguration
     */
    public function __construct(array $config)
    {
        if (!isset($config['key'])) {
            throw InvalidConfiguration::invalidCredential('sendinblue', 'key');
        }

        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $config['key']);
        $this->client = new SMTPApi(new Client(), $config, null);
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
        $this->message['sender'] = new SendSmtpEmailSender([
            'name' => $name,
            'email' => $email
        ]);

        return $this;
    }

    /**
     * @param string $template
     * @return Driver
     */
    public function setTemplate($template): Driver
    {
        $this->message['templateId'] = $template;

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
        $this->message['to'] = [new SendSmtpEmailTo([
            'name' => $name,
            'email' => $email
        ])];

        return $this;
    }

    /**
     * @param string $name
     * @param string $email
     * @return Driver
     */
    public function setBcc(string $name, string $email): Driver
    {
        $this->message['bcc'] = [new SendSmtpEmailBcc([
            'name' => $name,
            'email' => $email
        ])];

        return $this;
    }

    /**
     * @param array $variables
     * @return Driver
     */
    public function setVariables(array $variables): Driver
    {
        $params = [];
        foreach ($variables as $variableKey => $variableValue) {
            $params[$variableKey] = $variableValue;
        }

        $this->message['params'] = $params;

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
        $email = new SendSmtpEmail($this->message);

        try {
            $response = $this->client->sendTransacEmail($email);
            return ['messageId' => $response->getMessageId()];
        } catch (ApiException $exception) {
            throw SendError::responseError('sendinblue', 0, $exception);
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
