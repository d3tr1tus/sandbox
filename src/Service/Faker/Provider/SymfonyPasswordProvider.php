<?php declare(strict_types=1);

namespace App\Service\Faker\Provider;

use Faker\Generator;
use Faker\Provider\Base;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

final class SymfonyPasswordProvider extends Base
{

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * {@inheritdoc}
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(Generator $generator, EncoderFactoryInterface $encoderFactory)
    {
        parent::__construct($generator);
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param string $plainPassword
     * @return string
     */
    public function symfonyPassword(string $plainPassword): string
    {
        $password = $this->encoderFactory->getEncoder('App\\Entity\\User')->encodePassword($plainPassword, null);
        return $this->generator->parse($password);
    }

}