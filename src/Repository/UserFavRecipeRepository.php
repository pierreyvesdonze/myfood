<?php

namespace App\Repository;

use App\Entity\UserFavRecipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserFavRecipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFavRecipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFavRecipe[]    findAll()
 * @method UserFavRecipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFavRecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavRecipe::class);
    }

    /**
     * @return UserFavRecipe Returns a UserFavRecipe object
     */
    public function findExistingFavByUser($user)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.userId = :valUser')
            ->setParameter('valUser', $user)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?UserFavRecipe
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
