<?php

namespace App\Service\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CarIsFree extends Constraint
{
    public $message = 'The car is already reserved.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
