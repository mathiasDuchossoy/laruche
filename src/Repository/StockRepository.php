<?php

namespace App\Repository;

use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    public function countPays(int $id)
    {
        return $this->createQueryBuilder('s')
            ->select('count(r.countryCode)')
            ->innerJoin('s.gifts' , 'g')
            ->innerJoin('g.receivers' , 'r')
            ->groupBy('r.countryCode')
            ->getQuery()
            ->getResult()
            ;
    }

    public function avgPrices(int $id)
    {
        return $this->createQueryBuilder('s')
            ->select('avg(g.price) AS avgPrices')
            ->innerJoin('s.gifts' , 'g')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
