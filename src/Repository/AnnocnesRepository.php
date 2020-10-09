<?php

namespace App\Repository;

use App\Entity\Annocnes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Annocnes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annocnes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annocnes[]    findAll()
 * @method Annocnes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnocnesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annocnes::class);
    }

    // /**
    //  * @return Annocnes[] Returns an array of Annocnes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Annocnes
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
