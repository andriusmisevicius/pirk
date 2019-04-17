<?php

namespace App\Repository;

use App\Entity\ItemCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ItemCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemCategory[]    findAll()
 * @method ItemCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ItemCategory::class);
    }

    public function findById($id)
    {
        return $this
            ->createQueryBuilder('ic')
            ->andWhere('ic.id = :id')
            ->setParameter('id', $id)
            ->orderBy('ic.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return ItemCategory[] Returns an array of ItemCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemCategory
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
