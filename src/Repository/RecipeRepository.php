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

        $baseCondition = $qb->expr()->andX(
            $qb->expr()->eq('r.recipeMenu', ':reM')
        );

        if ($recipeMenu) {
            $optionalCondition = $qb->expr()->andX(
                $qb->expr()->eq('r.recipeCategory', ':rC')
            );

            $qb->where($qb->expr()->orX($baseCondition, $optionalCondition))
                ->setParameter('rC', $recipeCategory);
        } else {
            $qb->where($baseCondition);
        }

        $qb->setParameter('reM', $recipeMenu);

        return $qb->getQuery()->getResult();
    }
}
