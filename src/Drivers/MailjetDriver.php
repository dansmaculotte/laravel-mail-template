<?php

namespace DansMaCulotte\MailTemplate\Drivers;

use DansMaCulotte\MailTemplate\Exceptions\InvalidConfiguration;
use DansMaCulotte\MailTemplate\Exceptions\SendError;
use Mailjet\Client;
use Mailjet\Resources;

class MailjetDriver implements Driver
{
    /** @var Client|null  */
    private $mailjet = null;

    /** @var array */
    public $body = [];

    /** @var array */
    public $message = [];

    public function __construct($config)
    {
        if (!$config['key'] || !$config['secret']) {
            throw InvalidConfiguration::invalidCredentials('mailjet');
        }

        $this->mailjet = new Client($config['key'], $config['secret']);
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
    public function setTemplate(string $template): Driver
    {
        $this->message['TemplateID'] = $template;
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
        $this->message['To']['Name'] = $name;
        $this->message['To']['Email'] = $email;

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
        $response = $this->mailjet->post(Resources::$Email, [
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
