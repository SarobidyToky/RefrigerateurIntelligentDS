<?php

namespace App\Repository;

use App\Entity\Entree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Entree|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entree|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entree[]    findAll()
 * @method Entree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntreeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Entree::class);
    }

    public function sumProduit($id){
        $qb = $this->createQueryBuilder('e')
            ->select("SUM(e.quantiteEntree) as qt")
            ->where("e.produit = :id")
            ->setParameter('id' , $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function recuperationPremierLigne($id, $offset, $limit){
        $qb = $this->createQueryBuilder('e')
            ->select("e")
            ->where('e.produit = :id')
            ->setParameter("id", $id)
            ->orderBy("e.datePeremption", 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;


        return $qb->getQuery()->getSingleResult();

    }

    public function misAJourTable($restant){
        $qb = $this->createQueryBuilder('e')
            ->update('\App\Entity\Entree', 'e')
            ->set('e.quantiteEntree', '?1')
            ->setParameter('1', $restant)
        ;

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Entree[] Returns an array of Entree objects
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
    public function findOneBySomeField($value): ?Entree
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
