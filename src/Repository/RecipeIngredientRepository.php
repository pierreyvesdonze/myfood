<?php

namespace App\Repository;

use App\Entity\RecipeIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method RecipeIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeIngredient[]    findAll()
 * @method RecipeIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeIngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeIngredient::class);
    }

    /**
     * @return RecipeIngredient[] Returns an array of Ingredient objects
     */
    public function findRecipeByIngredients($keywords)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.name LIKE :val')
            ->setParameter('val', '%' . $keywords . '%')
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}