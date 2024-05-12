<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicle>
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }


    /**
     * Find vehicles with availabilities that overlap with the specified dates and maximum price
     *
     * @param \DateTimeInterface $start_date
     * @param \DateTimeInterface $end_date
     * @param float|null $maxPrice
     * @return array
     */
    public function findAvailableVehicles(\DateTimeInterface $start_date, \DateTimeInterface $end_date, ?float $maxPrice): array
    {
        $queryBuilder = $this->createQueryBuilder('vehicle')
            ->leftJoin('vehicle.availabilities', 'availability')
            ->andWhere('availability.start_date <= :end_date')
            ->andWhere('availability.end_date >= :start_date')
            ->setParameter('start_date', $start_date)
            ->setParameter('end_date', $end_date);

        if ($maxPrice !== null) {
            $queryBuilder
                ->andWhere('availability.price_per_day <= :max_price')
                ->setParameter('max_price', $maxPrice);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    //    /**
    //     * @return Vehicle[] Returns an array of Vehicle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vehicle
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
