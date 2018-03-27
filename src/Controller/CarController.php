<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Company;
use App\Entity\User;
use App\Exception\Doctrine\EntityNotFoundException;
use App\Repository\CarRepository;
use App\Response\JsonResponse;
use App\Service\Doctrine\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Martin Pánek <kontakt@martinpanek.cz>
 */
class CarController
{

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * @var CarRepository
     */
    private $carRepository;

    /**
     * @var \App\Repository\UserRepository
     */
    private $userRepository;

    /**
     * @var \App\Repository\CompanyRepository
     */
    private $companyRepository;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->carRepository = $this->em->getRepository(Car::class);
        $this->userRepository = $this->em->getRepository(User::class);
        $this->companyRepository = $this->em->getRepository(Company::class);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \App\Response\JsonResponse
     * @Route(path="/api/cars/{carId}", methods={"GET"})
     * @SWG\Get(
     *     path="/api/cars/{carId}", summary="get car by id", tags={"car"},
     *     @SWG\Parameter(name="carId", in="path", required=true, type="integer"),
     *     @SWG\Response(response="200", ref="#/definitions/CarModel", description="Returns car"),
     * )
     */
    public function getOne(Request $request): JsonResponse
    {
        $car = $this->carRepository->findOneBy(['id' => $request->get('carId')]);
        return new JsonResponse($car);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \App\Response\JsonResponse
     * @Route(path="/api/cars/", methods={"GET"})
     * @SWG\Get(
     *     path="/api/cars/", summary="list car", tags={"car"},
     *     @SWG\Parameter(name="companyId", in="query", type="integer", required=false),
     *     @SWG\Response(response="200", @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/CarModel")), description="Return car")),
     * ))
     */
    public function getList(Request $request): JsonResponse
    {
        $qb = $this->carRepository->createQueryBuilder('c');

        if ($companyId = $request->get('companyId')) {
            $qb->andWhere('c.company = :companyId')
                ->setParameter('companyId', $companyId);
        }

        return new JsonResponse(Paginator::packResponse($qb, $request));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \App\Response\JsonResponse
     * @throws \App\Exception\Doctrine\EntityAlreadyExistsException
     * @Route(path="/api/cars/", methods={"POST"})
     * @SWG\Post(
     *     path="/api/cars/", summary="create car", tags={"car"},
     *     @SWG\Parameter(name="body", in="body", @SWG\Schema(
     *          @SWG\Property(property="companyId", type="integer"),
     *          @SWG\Property(property="kilometres", type="float"),
     *          @SWG\Property(property="color", type="string"),
     *          @SWG\Property(property="marking", type="string"),
     *          @SWG\Property(property="yearOfManufacture", type="integer"),
     *     )),
     *     @SWG\Response(response="200", ref="#/definitions/Car", description="Returns created car"),
     * )
     */
    public function create(Request $request): JsonResponse
    {
        $car = new Car(
            $request->get('name'),
            $this->getCompany($request)
        );

        $this->updateCarByRequest($car, $request);

        $this->em->persist($car);
        $this->em->flush();

        return new JsonResponse($car);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \App\Response\JsonResponse
     * @throws \App\Exception\Doctrine\EntityNotFoundException
     * @Route(path="/api/cars/{carId}/", methods={"PUT"})
     * @SWG\Put(
     *     path="/api/cars/{carId}/", summary="update car", tags={"car"},
     *     @SWG\Parameter(name="carId", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="body", in="body", @SWG\Schema(
     *          @SWG\Property(property="kilometres", type="float"),
     *          @SWG\Property(property="color", type="string"),
     *          @SWG\Property(property="marking", type="string"),
     *          @SWG\Property(property="yearOfManufacture", type="integer"),
     *     )),
     *     @SWG\Response(response="200", ref="#/definitions/Car", description="Returns updated car"),
     * )
     */
    public function update(Request $request): JsonResponse
    {
        $car = $this->carRepository->findOneBy(['id' => $request->get('carId')]);
        if (!$car) {
            throw new EntityNotFoundException("Car not found");
        }

        $this->updateCarByRequest($car, $request);

        $this->em->flush();

        return new JsonResponse($car);
    }

    /**
     * @param \App\Entity\Car $car
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    private function updateCarByRequest(Car $car, Request $request)
    {
        if ($kilometres = $request->get('kilometres')) {
            $car->setKilometres($kilometres);
        }

        if ($color = $request->get('color')) {
            $car->setColor($color);
        }

        if ($yearOfManufacture = (int) $request->get('yearOfManufacture')) {
            $car->setYearOfManufacture($yearOfManufacture);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \App\Entity\Company|null
     * @throws \App\Exception\Doctrine\EntityNotFoundException
     */
    private function getCompany(Request $request): ?Company
    {
        $company = null;
        if ($companyId = $request->get('companyId')) {
            /** @var Company $company */
            $company = $this->companyRepository->findOneBy(['id' => $companyId]);
        }
        return $company;
    }

}
