<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Exception\Doctrine\EntityAlreadyExistsException;
use App\Response\JsonResponse;
use App\Service\Doctrine\Paginator;
use App\Service\Generator\RandomPasswordGenerator;
use App\Service\Mailer\Mailer;
use App\Service\Mailer\Message\UserCredentialsMessage;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Martin Pánek <kontakt@martinpanek.cz>
 */
class UserController
{

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * @var \App\Repository\UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \App\Service\Mailer\Mailer
     */
    private $mailer;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param \App\Service\Mailer\Mailer $mailer
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, Mailer $mailer)
    {
        $this->em = $em;
        $this->userRepository = $this->em->getRepository(User::class);
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \App\Response\JsonResponse
     * @Route(path="/api/users/{userId}", methods={"GET"})
     * @SWG\Get(
     *     path="/api/users/{userId}", summary="get user by id", tags={"users"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(response="200", ref="#/definitions/User", description="Returns user"),
     * )
     */
    public function getOne(Request $request): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $request->get('userId'), 'role' => User::ROLE_USER]);
        return new JsonResponse($user);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \App\Response\JsonResponse
     * @Route(path="/api/users/", methods={"GET"})
     * @SWG\Get(
     *     path="/api/users/", summary="list users", tags={"users"},
     *     @SWG\Parameter(name="phrase", in="query", type="string", required=false),
     *     @SWG\Parameter(name="page", in="query", type="number", required=false),
     *     @SWG\Parameter(name="itemsPerPage", in="query", type="number", required=false),
     *     @SWG\Response(response="200", description="Return users", @SWG\Schema(ref="#definitions/UserList")),
     * )
     * @SWG\Definition(
     *      definition="UserList",
     *      @SWG\Property(property="paginator", ref="#/definitions/Paginator"),
     *      @SWG\Property(property="items", type="array", @SWG\Items(ref="#/definitions/User")),
     * )
     */
    public function getList(Request $request): JsonResponse
    {
        $qb = $this->userRepository->createQueryBuilder('u')
            ->where('u.role = :role')
            ->setParameter('role', User::ROLE_USER)
            ->addOrderBy('u.isActive', 'desc');

        if ($phrase = $request->get('phrase')) {
            $qb->andWhere('
                u.email LIKE :phrase OR 
                u.firstName LIKE :phrase OR 
                u.lastName LIKE :phrase OR 
                u.identificationNumber LIKE :phrase OR 
                u.taxIdentificationNumber LIKE :phrase
            ')->setParameter('phrase', "%" . $phrase . "%");
        }

        return new JsonResponse(Paginator::packResponse($qb, $request));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \App\Response\JsonResponse
     * @throws \App\Exception\Doctrine\EntityAlreadyExistsException
     * @Route(path="/api/users/", methods={"POST"})
     * @SWG\Post(
     *     path="/api/users/", summary="create user", tags={"users"},
     *     @SWG\Parameter(name="body", in="body", @SWG\Schema(
 *              @SWG\Property(property="isPartner", type="boolean"),
     *          @SWG\Property(property="email", type="string"),
     *          @SWG\Property(property="password", type="string"),
     *          @SWG\Property(property="firstName", type="string"),
     *          @SWG\Property(property="lastName", type="string"),
     *          @SWG\Property(property="identificationNumber", type="string"),
     *          @SWG\Property(property="taxIdentificationNumber", type="string"),
     *          @SWG\Property(property="address", ref="#/definitions/Address"),
     *     )),
     *     @SWG\Response(response="200", ref="#/definitions/User", description="Returns created user"),
     * )
     */
    public function create(Request $request): JsonResponse
    {
        $user = new User($request->get('email'));
        $plainPassword = $request->get('password', RandomPasswordGenerator::generate());
        $hashedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $this->updateUserByRequest($user, $request);
        $this->em->persist($user);

        try {
            $this->em->flush();
            $this->mailer->send(new UserCredentialsMessage($user, $plainPassword));
        } catch (UniqueConstraintViolationException $e) {
            throw new EntityAlreadyExistsException("User already exists", $e->getCode(), $e);
        }

        return new JsonResponse($user);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \App\Response\JsonResponse
     * @Route(path="/api/users/{userId}/", methods={"PUT"})
     * @SWG\Put(
     *     path="/api/users/{userId}", summary="update user", tags={"users"},
     *     @SWG\Parameter(name="userId", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="body", in="body", @SWG\Schema(
     *          @SWG\Property(property="isPartner", type="boolean"),
     *          @SWG\Property(property="firstName", type="string"),
     *          @SWG\Property(property="lastName", type="string"),
     *          @SWG\Property(property="identificationNumber", type="string"),
     *          @SWG\Property(property="taxIdentificationNumber", type="string"),
     *          @SWG\Property(property="address", ref="#/definitions/Address"),
     *     )),
     *     @SWG\Response(response="200", ref="#/definitions/User", description="Returns created user"),
     * )
     */
    public function update(Request $request): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $request->get('userId'), 'role' => User::ROLE_USER]);

        $this->updateUserByRequest($user, $request);
        $this->em->flush();

        return new JsonResponse($user);
    }

    /**
     * @param \App\Entity\User $user
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    private function updateUserByRequest(User $user, Request $request)
    {
        if ($firstName = $request->get('firstName')) {
            $user->setFirstName($firstName);
        }

        if ($lastName = $request->get('lastName')) {
            $user->setLastName($lastName);
        }

        if ($phone = $request->get('phone')) {
            $user->setPhone($phone);
        }

        $isActive = $request->get('isActive');
        if ($isActive !== null) {
            $user->setIsActive($isActive);
        }

        $isPartner = $request->get('isPartner');
        if ($isPartner !== null) {
            $user->setIsPartner($isPartner);
        }

        if ($identificationNumber = $request->get('identificationNumber')) {
            $user->setIdentificationNumber($identificationNumber);
        }

        if ($taxIdentificationNumber = $request->get('taxIdentificationNumber')) {
            $user->setTaxIdentificationNumber($taxIdentificationNumber);
        }

        if ($address = $request->get('address')) {

            if ($country = $address['country'] ?? null) {
                $user->getAddress()->setCountry($country);
            }

            if ($city = $address['city'] ?? null) {
                $user->getAddress()->setCity($city);
            }

            if ($street = $address['street'] ?? null) {
                $user->getAddress()->setStreet($street);
            }

            if ($zip = $address['zip'] ?? null) {
                $user->getAddress()->setZip($zip);
            }

        }

    }

}
