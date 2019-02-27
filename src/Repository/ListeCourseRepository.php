<?php

namespace App\Repository;

use App\Entity\ListeCourse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ListeCourse|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListeCourse|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListeCourse[]    findAll()
 * @method ListeCourse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListeCourseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ListeCourse::class);
    }

    // /**
    //  * @return ListeCourse[] Returns an array of ListeCourse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ListeCourse
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
