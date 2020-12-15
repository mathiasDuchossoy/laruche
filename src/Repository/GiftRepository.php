<?php

namespace App\Repository;

use App\Entity\Gift;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gift|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gift|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gift[]    findAll()
 * @method Gift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GiftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gift::class);
    }

    public function findByPriceNotNull($idStock)
    {
        return $this->createQueryBuilder('g')
            ->select('g.price')
            ->andWhere('g.stock = :val')
            ->andWhere('g.price IS NOT NULL')
            ->setParameter('val', $idStock)
            ->orderBy('g.price', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
