<?php declare(strict_types=1);

namespace App\Entity;

use App\Exception\Exception;
use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarRepository")
 * @SWG\Definition()
 */
class Car
{

    const MARKING_MAGNETS = 'magnets';
    const MARKING_STICKERS = 'stickers';

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
     * @ORM\Column(type="string")
     * @SWG\Property(type="string")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $name;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="Company")
     * @SWG\Property(ref="#/definitions/Company")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $company;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @SWG\Property(type="float")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $kilometres;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @SWG\Property(type="string")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $color;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @SWG\Property(type="int")
     * @Serializer\Groups({"ROLE_UNAUTHENTICATED"})
     */
    protected $yearOfManufacture;

    /**
     * @param string $name
     * @param Company|null $company
     * @throws Exception
     */
    public function __construct($name, Company $company = NULL)
    {
        $this->name = $name;
        $this->company = $company;
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
     * @return Company
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor(string $color)
    {
        $this->color = $color;
    }

    /**
     * @return float
     */
    public function getKilometres(): float
    {
        return $this->kilometres;
    }

    /**
     * @param float $kilometres
     */
    public function setKilometres(float $kilometres)
    {
        $this->kilometres = $kilometres;
    }

    /**
     * @return int
     */
    public function getYearOfManufacture(): int
    {
        return $this->yearOfManufacture;
    }

    /**
     * @param int $yearOfManufacture
     */
    public function setYearOfManufacture(int $yearOfManufacture)
    {
        $this->yearOfManufacture = $yearOfManufacture;
    }

}
