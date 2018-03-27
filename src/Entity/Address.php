<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 * @SWG\Definition()
 */
class Address
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @SWG\Property()
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     * @Assert\Country(message="Je nutné vybrat platnou zemi")
     */
    protected $country;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $city;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $street;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $zip;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry($country = null)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(string $city = null)
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     */
    public function setStreet(string $street = null)
    {
        $this->street = $street;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param string|null $zip
     */
    public function setZip(string $zip = null)
    {
        $this->zip = $zip;
    }

}
