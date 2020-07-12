<?php

namespace App\Repository;

use App\Entity\RecipeMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RecipeMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeMenu[]    findAll()
 * @method RecipeMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeMenu::class);
    }

    // /**
    //  * @return RecipeMenu[] Returns an array of RecipeMenu objects
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
    public function findOneBySomeField($value): ?RecipeMenu
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
