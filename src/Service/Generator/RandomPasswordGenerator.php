<?php

namespace App\Service\Generator;

class RandomPasswordGenerator
{

    public static function generate($length = 4) {
        return bin2hex(random_bytes($length));
    }

}
