<?php

namespace App\Service\Mailer\Message;

use App\Entity\User;

/**
 * @author Martin Pánek <kontakt@martinpanek.cz>
 */
class DriverCredentialsMessage extends Message
{

    public function __construct(User $user, string $password)
    {
        $this->subject = "Přihlašovací údaje";

        $this->addRecipient($user->getEmail(), $user->getFullName());

        $this->data = [
            "user" => $user,
            "password" => $password,
        ];
    }

}