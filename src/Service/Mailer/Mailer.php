<?php

namespace App\Service\Mailer;

use App\Exception\Service\Mailer\MissingRecipientsException;
use App\Exception\Service\Mailer\MissingSubjectException;
use App\Exception\Service\Mailer\MissingTemplateException;
use App\Service\Mailer\Message\IMessage;

/**
 * @author Martin Pánek <kontakt@martinpanek.cz>
 */
class Mailer
{

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    /**
     * @param \App\Service\Mailer\Message\IMessage $message
     * @throws \App\Exception\Service\Mailer\MissingRecipientsException
     * @throws \App\Exception\Service\Mailer\MissingSubjectException
     * @throws \App\Exception\Service\Mailer\MissingTemplateException
     */
    public function send(IMessage $message)
    {
        if (strlen($message->getSubject()) === 0) {
            throw new MissingSubjectException();
        }

        $swiftMessage = new \Swift_Message($message->getSubject());
        $swiftMessage->setFrom(getenv('MAILER_FROM'));

        if (count($message->getRecipients()) === 0) {
            throw new MissingRecipientsException();
        }

        foreach ($message->getRecipients() as $email => $name) {
            $swiftMessage->addTo($email, $name);
        }

        try {
            $html = $this->twig->render("emails/{$message->getTemplateName()}", $message->getData());
        } catch (\Twig_Error_Loader $e) {
            throw new MissingTemplateException($e->getMessage(), $e->getCode(), $e);
        }

        $swiftMessage->setBody($html, 'text/html');
        $this->mailer->send($swiftMessage);
    }

}
