<?php

namespace DansMaCulotte\MailTemplate\Drivers;

use DansMaCulotte\MailTemplate\Exceptions\InvalidConfiguration;
use DansMaCulotte\MailTemplate\Exceptions\SendError;
use SendGrid;
use SendGrid\Mail\Mail;

class SendgridDriver implements Driver
{

    /** @var SendGrid|null  */
    public $client = null;

    /** @var array */
    public $body = [];

    /** @var SendGrid\Mail\Mail */
    public $message;

    /**
     * Driver constructor.
     * @param array $config
     * @throws InvalidConfiguration
     */
    public function __construct(array $config)
    {
        if (!isset($config['key'])) {
            throw InvalidConfiguration::invalidCredential('sendgrid', 'key');
        }

        $this->client = new SendGrid($config['key']);
        $this->message = new Mail();
    }

    /**
     * @param string $name
     * @param string $email
     * @return Driver
     * @throws SendGrid\Mail\TypeException
     */
    public function setFrom(string $name, string $email): Driver
    {
        $this->message->setFrom($email, $name);

        return $this;
    }

    /**
     * @param string $templateId
     * @return Driver
     */
    public function setTemplate($templateId): Driver
    {
        $this->message->setTemplateId($templateId);

        return $this;
    }

    /**
     * @param string $subject
     * @return Driver
     */
    public function setSubject(string $subject): Driver
    {
        $this->message->setSubject($subject);

        return $this;
    }

    /**
     * @param string $name
     * @param string $email
     * @return Driver
     */
    public function setRecipient(string $name, string $email): Driver
    {
        $this->message->addTo($email, $name);

        return $this;
    }

    /**
     * @param array $variables
     * @return Driver
     */
    public function setVariables(array $variables): Driver
    {
        foreach ($variables as $key => $value) {
            $this->message->addSubstitution($key, $value);
        }

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
     * @param string $file
     * @param string $name
     * @return Driver
     */
    public function addAttachment(string $file, string $name): Driver
    {
        $fileEncoded = base64_encode(file_get_contents($file));
        $fileType = mime_content_type($file);

        $this->message->addAttachment($fileEncoded, $fileType, $name);

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
        try {
            $response = $this->client->send($this->message);
            return $response->headers();
        } catch (\Exception $exception) {
            throw SendError::responseError('sendgrid', 0, $exception);
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'body' => array_merge($this->body, [
                'message' => $this->message,
            ]),
        ];
    }
}
