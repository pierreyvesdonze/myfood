<?php

namespace App\DataFixtures;

use App\Entity\RecipeCategory;
use App\Entity\RecipeMenu;
use App\Entity\Role;
use App\Entity\Tag;
use App\Entity\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    //php bin/console doctrine:fixtures:load
    public function load(ObjectManager $manager)
    {

        // Units
        $unit = new Unit;
        $unit->setName('unité');
        $manager->persist($unit);

        $mg = new Unit;
        $mg->setName('mg');
        $manager->persist($mg);

        $g = new Unit;
        $g->setName('g');
        $manager->persist($g);
        $manager->flush();

        $kg = new Unit;
        $kg->setName('kg');
        $manager->persist($kg);

        $ml = new Unit;
        $ml->setName('ml');
        $manager->persist($ml);

        $dl = new Unit;
        $dl->setName('dl');
        $manager->persist($dl);

        $cl = new Unit;
        $cl->setName('cl');
        $manager->persist($cl);

        $l = new Unit;
        $l->setName('Litre');
        $manager->persist($l);

        $pincee = new Unit;
        $pincee->setName('pincée');
        $manager->persist($pincee);

        $cf = new Unit;
        $cf->setName('cuillère à café');
        $manager->persist($cf);

        $cs = new Unit;
        $cs->setName('cuillère à soupe');
        $manager->persist($cs);

        $tasse = new Unit;
        $tasse->setName('tasse');
        $manager->persist($tasse);

        // Type
        $entree = new RecipeMenu;
        $entree->setName('Entrée');
        $manager->persist($entree);

        $plat = new RecipeMenu;
        $plat->setName('Plat');
        $manager->persist($plat);

        $dessert = new RecipeMenu;
        $dessert->setName('Dessert');
        $manager->persist($dessert);

        $boisson = new RecipeMenu;
        $boisson->setName('Boisson');
        $manager->persist($boisson);


        // Category
        $pates = new RecipeCategory;
        $pates->setName('Pâtes');
        $manager->persist($pates);

        $salade = new RecipeCategory;
        $salade->setName('Salade');
        $manager->persist($salade);

        $legumes = new RecipeCategory;
        $legumes->setName('Légumes');
        $manager->persist($legumes);

        $riz = new RecipeCategory;
        $riz->setName('Riz');
        $manager->persist($riz);

        $viande = new RecipeCategory;
        $viande->setName('Viande');
        $manager->persist($viande);

        $poisson = new RecipeCategory;
        $poisson->setName('Poisson');
        $manager->persist($poisson);

        $oeufs = new RecipeCategory;
        $oeufs->setName('Oeufs');
        $manager->persist($oeufs);

        $soupe = new RecipeCategory;
        $soupe->setName('Soupe');;
        $manager->persist($soupe);

        $pizza = new RecipeCategory;
        $pizza->setName('Tarte/Quiche/Pizza');
        $manager->persist($pizza);

        $burger = new RecipeCategory;
        $burger->setName('Burger/Sandwich');
        $manager->persist($burger);

        $fruitsdemer = new RecipeCategory;
        $fruitsdemer->setName('Friuts de mer');
        $manager->persist($fruitsdemer);

        $sauce = new RecipeCategory;
        $sauce->setName('Sauce');
        $manager->persist($sauce);

        $platmijot = new RecipeCategory;
        $platmijot->setName('Plat mijoté');
        $manager->persist($platmijot);

        $gateau = new RecipeCategory;
        $gateau->setName('Gâteau');
        $manager->persist($gateau);

        $tartesucree = new RecipeCategory;
        $tartesucree->setName('Tarte sucrée');
        $manager->persist($tartesucree);

        $glace = new RecipeCategory;
        $glace->setName('Glace');
        $manager->persist($glace);

        // Tags
        $sansGluten = new Tag;
        $sansGluten->setName('Sans gluten');
        $manager->persist($sansGluten);

        $sansLactose = new Tag;
        $sansLactose->setName('Sans lactose');
        $manager->persist($sansLactose);

        $sansOeufs = new Tag;
        $sansOeufs->setName('Sans oeufs');
        $manager->persist($sansOeufs);

        $vegetarien = new Tag;
        $vegetarien->setName('Végétarien');
        $manager->persist($vegetarien);

        $vegan = new Tag;
        $vegan->setName('Végan');
        $manager->persist($vegan);

        $rapide = new Tag;
        $rapide->setName('Rapide');
        $manager->persist($rapide);

        $junkFood = new Tag;
        $junkFood->setName('Junk Food');
        $manager->persist($junkFood);

        $economic = new Tag;
        $economic->setName('Economique');
        $manager->persist($economic);

        // Roles
        $roleUser = new Role;
        $roleUser->setName('ROLE_USER');
        $roleUser->setRoleString('User');
        $manager->persist($roleUser);

        $roleAdmin = new Role;
        $roleAdmin->setName('ROLE_ADMIN');
        $roleAdmin->setRoleString('Admin');
        $manager->persist($roleAdmin);

        $manager->flush();
    }
}
