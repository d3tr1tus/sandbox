<?php

namespace App\Service\Mailer\Message;

/**
 * @author Martin Pánek <kontakt@martinpanek.cz>
 */
class Message implements IMessage {

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $subject = "";

    /**
     * @var array email => name
     */
    protected $recipients = [];

    public function getTemplateName(): string
    {
        $class = get_class($this);
        $class = str_replace("App\\Service\\Mailer\\Message\\", "", $class);
        $class = str_replace("Message", "", $class);
        $class[0] = strtolower($class[0]);

        return $class . ".html.twig";
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function addRecipient(string $email, string $name = null): void
    {
        $this->recipients[$email] = $name;
    }
}