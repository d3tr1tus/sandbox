<?php

namespace App\Service\Mailer\Message;

interface IMessage {

    public function getTemplateName(): string;

    public function getData(): array;

    public function getSubject(): string;

    public function getRecipients(): array;

    public function addRecipient(string $email, string $name = null): void;

}
