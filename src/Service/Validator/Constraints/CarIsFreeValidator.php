<?php

namespace App\Service\Validator\Constraints;

use App\Entity\WorkingTime;
use App\Exception\Service\Validator\CarAlreadyInUseException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CarIsFreeValidator extends ConstraintValidator
{

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * @var \App\Repository\WorkingTimeRepository
     */
    private $workingTimeRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->workingTimeRepository = $this->em->getRepository(WorkingTime::class);
    }

    /**
     * @param \App\Entity\WorkingTime $workingTime
     * @param \Symfony\Component\Validator\Constraint $constraint
     * @throws \App\Exception\Service\Validator\CarAlreadyInUseException
     */
    public function validate($workingTime, Constraint $constraint)
    {
        try {
            $this->workingTimeRepository->createQueryBuilder('wt')
                ->andWhere('(:timeFrom BETWEEN wt.timeFrom AND wt.timeTo) OR (:timeTo BETWEEN wt.timeFrom AND wt.timeTo)')
                ->setParameter('timeFrom', $workingTime->getTimeFrom())
                ->setParameter('timeTo', $workingTime->getTimeTo())
                ->andWhere('wt.car = :car')
                ->setParameter('car', $workingTime->getCar())
                ->andWhere('wt.date = :date')
                ->setParameter('date', $workingTime->getDate())
                ->getQuery()->getSingleResult();

            throw new CarAlreadyInUseException();
        } catch (NoResultException $e) {}
    }
}
