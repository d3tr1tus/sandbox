<?php declare(strict_types=1);

namespace App\Service\EventListener;

use App\Exception\Doctrine\ValidationException;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Martin Pánek <kontakt@martinpanek.cz>
 */
class DoctrineEntityValidationListener
{

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private $validator;

    /**
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     * @throws \App\Exception\Doctrine\ValidationException
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->validate($args);
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     * @throws \App\Exception\Doctrine\ValidationException
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->validate($args);
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     * @throws \App\Exception\Doctrine\ValidationException
     */
    private function validate(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        $errors = $this->validator->validate($entity);

        $messages = [];
        /** @var \Symfony\Component\Validator\ConstraintViolation $error */
        foreach ($errors as $error) {
//            $messages[] = "Pole {$error->getPropertyPath()}: {$error->getMessage()}";
            $messages[] = "{$error->getMessage()}.";
        }

        if ($errors->count() > 0) {
            throw new ValidationException(join(' ', $messages));
        }
    }

}