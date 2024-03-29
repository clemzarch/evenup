<?php

namespace App\Repository;

use App\Entity\MapadoIDs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MapadoIDs|null find($id, $lockMode = null, $lockVersion = null)
 * @method MapadoIDs|null findOneBy(array $criteria, array $orderBy = null)
 * @method MapadoIDs[]    findAll()
 * @method MapadoIDs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MapadoIDsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MapadoIDs::class);
    }

    // /**
    //  * @return MapadoIDs[] Returns an array of MapadoIDs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MapadoIDs
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
