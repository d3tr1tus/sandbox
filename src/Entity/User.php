<?php declare(strict_types=1);

namespace App\Entity;

use App\Exception\Service\Validator\WrongPhoneNumberFormatException;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Security\Core\User\UserInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @SWG\Definition()
 */
class User implements UserInterface, \Serializable
{

    const ROLE_USER = "ROLE_USER";
    const ROLE_DRIVER = "ROLE_DRIVER";
    const ROLE_COMPANY_MANAGER = "ROLE_COMPANY_MANAGER";
    const ROLE_ADMIN = "ROLE_ADMIN";

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
     * @SWG\Property(type="string")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     * @Assert\Email()
     */
    protected $email;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $firstName;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $lastName;

    /**
     * @var PhoneNumber
     * @ORM\Column(type="phone_number", nullable=true)
     * @SWG\Property(type="string")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $phone;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $identificationNumber;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $taxIdentificationNumber;

    /**
     * @var Address
     * @ORM\OneToOne(targetEntity="Address", cascade={"persist"})
     * @SWG\Property(ref="#/definitions/Address")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    private $address;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="Company", cascade={"persist"})
     * @SWG\Property(ref="#/definitions/Company")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    private $company;

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     * @SWG\Property()
     * @Assert\Length(min="6")
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @SWG\Property(enum={"ROLE_USER", "ROLE_DRIVER", "ROLE_COMPANY_MANAGER", "ROLE_ADMIN"})
     * @Assert\Choice({"ROLE_USER", "ROLE_DRIVER", "ROLE_COMPANY_MANAGER", "ROLE_ADMIN"})
     * @Serializer\Groups({"ROLE_ADMIN"})
     */
    protected $role = self::ROLE_USER;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @SWG\Property()
     * @Serializer\Groups({"ROLE_ADMIN"})
     */
    private $isActive = true;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @SWG\Property()
     * @Serializer\Groups({"ROLE_ADMIN"})
     */
    private $isPartner = false;

    /**
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(string $firstName = null)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(string $lastName = null)
    {
        $this->lastName = $lastName;
    }

    /**
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     * @return null|string
     */
    public function getFullName(): ?string
    {
        if ($this->getFirstName() || $this->getLastName()) {
            return "{$this->getFirstName()} {$this->getLastName()}";
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getIdentificationNumber(): ?string
    {
        return $this->identificationNumber;
    }

    /**
     * @param string|null $identificationNumber
     */
    public function setIdentificationNumber(string $identificationNumber = null)
    {
        $this->identificationNumber = $identificationNumber;
    }

    /**
     * @return string|null
     */
    public function getTaxIdentificationNumber(): ?string
    {
        return $this->taxIdentificationNumber;
    }

    /**
     * @param string|null $taxIdentificationNumber
     */
    public function setTaxIdentificationNumber(string $taxIdentificationNumber = null)
    {
        $this->taxIdentificationNumber = $taxIdentificationNumber;
    }

    /**
     * @return PhoneNumber
     */
    public function getPhone(): ?PhoneNumber
    {
        return $this->phone;
    }

    /**
     * @param PhoneNumber|string|null $phone
     * @throws \App\Exception\Service\Validator\WrongPhoneNumberFormatException
     */
    public function setPhone($phone = null)
    {
        $isValid = true;

        if ($phone instanceof PhoneNumber || $phone === null) {
            if ($phone instanceof PhoneNumber) {
                $isValid = PhoneNumberUtil::getInstance()->isValidNumber($phone);
            }
            $this->phone = $phone;
        } else {
            try {
                $this->phone = PhoneNumberUtil::getInstance()->parse($phone);
            } catch (NumberParseException $e) {
                $isValid = false;
            }
        }

        if (!$isValid) {
            throw new WrongPhoneNumberFormatException("Telefonní číslo musí být v mezinárodním formátu +XXXXXXXXXXX");
        }
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company|null $company
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;
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

    /**
     * @return bool
     */
    public function isPartner(): bool
    {
        return $this->isPartner;
    }

    /**
     * @param bool $isPartner
     */
    public function setIsPartner(bool $isPartner)
    {
        $this->isPartner = $isPartner;
    }

    /******************** SECURITY ********************/

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return NULL;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return [$this->role];
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role)
    {
        $this->role = $role;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @inheritDoc
     */
    public function equals(UserInterface $user)
    {
        return $this->id === $user->getId();
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
        ) = unserialize($serialized);
    }

    public static function createSalt() {
        return md5(uniqid(null, true));
    }
}
