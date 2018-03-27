<?php

namespace App\Service\Validator;

use App\Entity\WorkingTime;
use App\Exception\Service\Validator\CarAlreadyInUseException;
use App\Exception\Service\Validator\WorkingTimeOverlapsException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Martin Pánek <kontakt@martinpanek.cz>
 */
class WorkingTimeValidator
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

    public function validate(WorkingTime $workingTime)
    {

        // ORG: 7 ------ 9
        //           8 ------- 10

        // ORG: 7 ------ 9
        //  6 ----- 8

        $workingTime = $this->workingTimeRepository->createQueryBuilder('wt')
            ->andWhere('(:timeFrom BETWEEN w.timeFrom AND w.timeTo) OR (:timeTo BETWEEN w.timeFrom AND w.timeTo)')
            ->setParameter('timeFrom', $workingTime->getTimeFrom())
            ->setParameter('timeTo', $workingTime->getTimeTo())
            ->andWhere('wt.driver = :driver')
            ->setParameter('driver', $workingTime->getDriver())
            ->andWhere('wt.date = :date')
            ->setParameter('date', $workingTime->getDate())
            ->getQuery()->getSingleResult();

        if ($workingTime) {
            throw new WorkingTimeOverlapsException();
        }

        $workingTime = $this->workingTimeRepository->createQueryBuilder('wt')
            ->andWhere('(:timeFrom BETWEEN w.timeFrom AND w.timeTo) OR (:timeTo BETWEEN w.timeFrom AND w.timeTo)')
            ->setParameter('timeFrom', $workingTime->getTimeFrom())
            ->setParameter('timeTo', $workingTime->getTimeTo())
            ->andWhere('wt.car = :ca')
            ->setParameter('car', $workingTime->getCar())
            ->andWhere('wt.date = :date')
            ->setParameter('date', $workingTime->getDate())
            ->getQuery()->getSingleResult();

        if ($workingTime) {
            throw new CarAlreadyInUseException();
        }
    }

}
