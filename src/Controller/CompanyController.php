<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Exception\Doctrine\EntityAlreadyExistsException;
use App\Response\JsonResponse;
use App\Service\Doctrine\Paginator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Martin Pánek <kontakt@martinpanek.cz>
 */
class CompanyController
{

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

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
        $this->companyRepository = $this->em->getRepository(Company::class);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \App\Response\JsonResponse
     * @Route(path="/api/companies/{companyId}", methods={"GET"})
     * @SWG\Get(
     *     path="/api/companies/{companyId}", summary="get company by id", tags={"companies"},
     *     @SWG\Parameter(name="companyId", in="path", required=true, type="integer"),
     *     @SWG\Response(response="200", ref="#/definitions/Company", description="Returns company"),
     * )
     */
    public function getOne(Request $request): JsonResponse
    {
        $company = $this->companyRepository->findOneBy(['id' => $request->get('companyId')]);
        return new JsonResponse($company);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \App\Response\JsonResponse
     * @Route(path="/api/companies/", methods={"GET"})
     * @SWG\Get(
     *     path="/api/companies/", summary="list companies", tags={"companies"},
     *     @SWG\Parameter(name="page", in="query", type="number", required=false),
     *     @SWG\Parameter(name="itemsPerPage", in="query", type="number", required=false),
     *     @SWG\Response(response="200", description="Return companies", @SWG\Schema(ref="#definitions/CompanyList")),
     * )
     * @SWG\Definition(
     *      definition="CompanyList",
     *      @SWG\Parameter(name="phrase", in="query", type="string", required=false),
     *      @SWG\Property(property="paginator", ref="#/definitions/Paginator"),
     *      @SWG\Property(property="items", type="array", @SWG\Items(ref="#/definitions/Company")),
     * )
     */
    public function getList(Request $request): JsonResponse
    {
        $qb = $this->companyRepository->createQueryBuilder('c')
            ->addOrderBy('c.isActive', 'desc');

        if ($phrase = $request->get('phrase')) {
            $qb->andWhere('
                c.name LIKE :phrase OR 
                c.identificationNumber LIKE :phrase OR 
                c.taxIdentificationNumber LIKE :phrase
            ')->setParameter('phrase', "%" . $phrase . "%");
        }

        return new JsonResponse(Paginator::packResponse($qb, $request));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \App\Response\JsonResponse
     * @throws \App\Exception\Doctrine\EntityAlreadyExistsException
     * @Route(path="/api/companies/", methods={"POST"})
     * @SWG\Post(
     *     path="/api/companies/", summary="create company", tags={"companies"},
     *     @SWG\Parameter(name="body", in="body", @SWG\Schema(
     *          @SWG\Property(property="name", type="string"),
     *          @SWG\Property(property="identificationNumber", type="string"),
     *          @SWG\Property(property="taxIdentificationNumber", type="string"),
     *          @SWG\Property(property="address", ref="#/definitions/Address"),
     *     )),
     *     @SWG\Response(response="200", ref="#/definitions/Company", description="Returns created company"),
     * )
     */
    public function create(Request $request): JsonResponse
    {
        $company = new Company();
        $this->updateCompanyByRequest($company, $request);
        $this->em->persist($company);

        try {
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new EntityAlreadyExistsException("Company already exists", $e->getCode(), $e);
        }

        return new JsonResponse($company);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \App\Response\JsonResponse
     * @Route(path="/api/companies/{companyId}", methods={"PUT"})
     * @SWG\Put(
     *     path="/api/companies/{companyId}", summary="update company", tags={"companies"},
     *     @SWG\Parameter(name="companyId", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="body", in="body", @SWG\Schema(
     *          @SWG\Property(property="name", type="string"),
     *          @SWG\Property(property="identificationNumber", type="string"),
     *          @SWG\Property(property="taxIdentificationNumber", type="string"),
     *          @SWG\Property(property="address", ref="#/definitions/Address"),
     *     )),
     *     @SWG\Response(response="200", ref="#/definitions/Company", description="Returns created company"),
     * )
     */
    public function update(Request $request): JsonResponse
    {
        $company = $this->companyRepository->findOneBy(['id' => $request->get('companyId')]);

        $this->updateCompanyByRequest($company, $request);
        $this->em->flush();

        return new JsonResponse($company);
    }

    /**
     * @param \App\Entity\Company $company
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    private function updateCompanyByRequest(Company $company, Request $request)
    {
        if ($name = $request->get('name')) {
            $company->setName($name);
        }

        $isActive = $request->get('isActive');
        if ($isActive !== null) {
            $company->setIsActive($isActive);
        }

        if ($identificationNumber = $request->get('identificationNumber')) {
            $company->setIdentificationNumber($identificationNumber);
        }

        if ($taxIdentificationNumber = $request->get('taxIdentificationNumber')) {
            $company->setTaxIdentificationNumber($taxIdentificationNumber);
        }

        if ($address = $request->get('address')) {

            if ($country = $address['country'] ?? null) {
                $company->getAddress()->setCountry($country);
            }

            if ($city = $address['city'] ?? null) {
                $company->getAddress()->setCity($city);
            }

            if ($street = $address['street'] ?? null) {
                $company->getAddress()->setStreet($street);
            }

            if ($zip = $address['zip'] ?? null) {
                $company->getAddress()->setZip($zip);
            }
        }
    }

}
