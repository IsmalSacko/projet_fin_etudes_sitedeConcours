<?php

namespace App\Repository;

use App\Entity\TypeConcours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeConcours|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeConcours|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeConcours[]    findAll()
 * @method TypeConcours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeConcoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeConcours::class);
    }

    // /**
    //  * @return TypeConcours[] Returns an array of TypeConcours objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeConcours
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
