<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 * @SWG\Definition()
 */
class Company
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
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @SWG\Property()
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     * @Assert\Length(min="5", minMessage="Název firmy musí mít alespoň 5 znaků")
     * @Assert\NotBlank(message="Název firmy je povinný")
     */
    protected $name = "";

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @SWG\Property()
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     * @Assert\NotBlank(message="IČ je povinné")
     */
    protected $identificationNumber;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @SWG\Property()
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     * @Assert\NotBlank(message="DIČ je povinné")
     */
    protected $taxIdentificationNumber;

    /**
     * @var Address
     * @ORM\OneToOne(targetEntity="Address", cascade={"persist"})
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     * @SWG\Property(ref="#/definitions/Address")
     */
    private $address;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @SWG\Property()
     * @Serializer\Groups({"ROLE_ADMIN"})
     */
    private $isActive = true;

    public function __construct()
    {
        $this->address = new Address();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIdentificationNumber(): string
    {
        return $this->identificationNumber;
    }

    /**
     * @param string $identificationNumber
     */
    public function setIdentificationNumber(string $identificationNumber)
    {
        $this->identificationNumber = $identificationNumber;
    }

    /**
     * @return string
     */
    public function getTaxIdentificationNumber(): string
    {
        return $this->taxIdentificationNumber;
    }

    /**
     * @param string $taxIdentificationNumber
     */
    public function setTaxIdentificationNumber(string $taxIdentificationNumber)
    {
        $this->taxIdentificationNumber = $taxIdentificationNumber;
    }

    /**
     * @return \App\Entity\Address
     */
    public function getAddress(): \App\Entity\Address
    {
        return $this->address;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
    }


}
