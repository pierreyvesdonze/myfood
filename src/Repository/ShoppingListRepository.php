<?php

namespace App\Repository;

use App\Entity\ShoppingList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ShoppingList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoppingList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoppingList[]    findAll()
 * @method ShoppingList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingList::class);
    }

    /**
     * @return Amount[] Returns an array of Amount objects
     */
    public function findIngredientsExcept($value)
    {
        return $this->createQueryBuilder('a')
          ->andWhere('a.api_id = :val')
          ->setParameter('val', $value)
          ->getQuery()
          ->getResult()
      ;
    }

    public function findOneByIdAndUser($id, $user)
    {
        return $this->createQueryBuilder('s')
            ->where('s.id = :shoplist')
            ->andWhere('s.user_id = :user')
            ->setParameter('shoplist', $id)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByUser($user)
    {
        return $this->createQueryBuilder('s')
            ->where('s.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
