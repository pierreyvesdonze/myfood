<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @return Recipe[] Returns an array of Recipe objects
     */
    public function findRecipeByName($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.name LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Recipe[] Returns an array of Ingredient objects
     */
    public function findRecipeByIngredients($keywords)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.name IN (:val)')
            ->setParameter('val', '%' . ',', $keywords . '%')
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @return Recipe[] Returns an array of Recipies objects
     */
    public function findFavsRecipiesByUser($favsIds)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :val')
            ->setParameter('val', $favsIds)
            ->getQuery()
            ->getArrayResult();
    }

    public function findRecipiesByFilters($recipeMenu, $recipeCategory)
    {
        $qb = $this->createQueryBuilder('r');

        $menuCondition = $qb->expr()->andX(
            $qb->expr()->eq('r.recipeMenu', ':rM')
        );
        $recipeCondition = $qb->expr()->andX(
            $qb->expr()->eq('r.recipeCategory', ':rC')
        );

        if ($recipeMenu && $recipeCategory) {

            $qb->where($qb->expr()->andX($menuCondition, $recipeCondition));
        } elseif ($recipeMenu || $recipeCategory) {
            $qb->where($qb->expr()->orX($menuCondition, $recipeCondition));
        }

        $qb->setParameter('rM', $recipeMenu);
        $qb->setParameter('rC', $recipeCategory);
        
        return $qb->getQuery()->getResult();
    }

    // public function findRecipiesByFilters($recipeMenu, $recipeCategory)
    // {
    //     $qb = $this->createQueryBuilder('r');

    //     $menuCondition = $qb->expr()->andX(
    //         $qb->expr()->eq('r.recipeMenu', ':rM')
    //     );
    //     $recipeCondition = $qb->expr()->andX(
    //         $qb->expr()->eq('r.recipeCategory', ':rC')
    //     );

    //     if ($recipeMenu) {

    //         $qb->where($qb->expr()->orX($menuCondition, $recipeCondition))
    //             ->setParameter('rC', $recipeCategory);

    //         $qb->setParameter('rM', $recipeMenu);
    //     } else {
    //         //$qb->where($menuCondition);
    //         $qb->where($qb->expr()->eq('r.recipeCategory', ':rC'));
    //         $qb->setParameter('rC', $recipeCategory);
    //     }


    //     return $qb->getQuery()->getResult();
    // }
}
