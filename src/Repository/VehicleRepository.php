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

            // Left join with the availabilities relation
            ->leftJoin('vehicle.availabilities', 'availability')

            // Check for availabilities that overlap with the specified dates
            ->andWhere('availability.start_date <= :end_date')
            ->andWhere('availability.end_date >= :start_date')

            // Set parameters for the start and end dates
            ->setParameter('start_date', $start_date)
            ->setParameter('end_date', $end_date);

        // If a maximum price is provided, add a condition to the query
        if ($maxPrice !== null) {
            $queryBuilder
                ->andWhere('availability.price_per_day <= :max_price')
                ->setParameter('max_price', $maxPrice);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
