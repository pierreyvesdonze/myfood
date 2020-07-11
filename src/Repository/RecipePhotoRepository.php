<?php

namespace App\Repository;

use App\Entity\RecipePhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RecipePhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipePhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipePhoto[]    findAll()
 * @method RecipePhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipePhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipePhoto::class);
    }

    // /**
    //  * @return RecipePhoto[] Returns an array of RecipePhoto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RecipePhoto
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
