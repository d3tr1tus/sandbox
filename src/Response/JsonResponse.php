<?php declare(strict_types=1);

namespace App\Response;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;

/**
 * @author Martin Pánek <kontakt@martinpanek.cz>
 */
class JsonResponse extends \Symfony\Component\HttpFoundation\Response
{

    /**
     * @var mixed
     */
    private $data;

    public function __construct($data = null, $status = 200, array $headers = [])
    {
        parent::__construct("", $status, $headers);
        $this->data = $data;

        if (!$this->headers->has('Content-Type')) {
            $this->headers->set('Content-Type', 'application/json');
        }
    }

    public function convertToJson(array $roles)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $normalizer->setCallbacks([
            'phone' => function(PhoneNumber $phone = null) {
                if ($phone === null) {
                    return null;
                }
                return PhoneNumberUtil::getInstance()->format($phone, PhoneNumberFormat::E164);
            }
        ]);

        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);
        $this->content = $serializer->serialize($this->data, 'json', ['groups' => $roles]);
    }

}
