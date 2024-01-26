<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 *
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function findHighestAndLatest(
        Car $car,
        int $maxCount = null,
        int $rateHigherThan = null
    ): mixed {
        $query = $this->createQueryBuilder('q')
            ->where('q.car = :carId')
            ->setParameter('carId', $car->getId());

        if ($rateHigherThan) {
            $query->andWhere('q.rate > :rate')
                ->setParameter('rate', $rateHigherThan);
        }

        if ($maxCount) {
            $query->setMaxResults($maxCount);
        }

        return $query
            ->orderBy('q.rate', 'DESC')
            ->addOrderBy('q.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
