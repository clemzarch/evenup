<?php

namespace App\Repository;

use App\Entity\EventbriteIDs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EventbriteIDs|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventbriteIDs|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventbriteIDs[]    findAll()
 * @method EventbriteIDs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventbriteIDsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EventbriteIDs::class);
    }

    // /**
    //  * @return EventbriteIDs[] Returns an array of EventbriteIDs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventbriteIDs
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
