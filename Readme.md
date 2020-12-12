# MyFood

## Préambule

**Warning**
Ce projet est avant tout un challenge personnel pour étoffer mon portfolio. Merci de le fork plutôt que de le cloner si vous souhaitez l'utiliser.

 Myfood est un site de recettes de cuisine avec pour avantage qu'il permet de créer ses propres recettes et de générer des listes de courses en fonction

--

## Créer ses propres recetettes
Chaque utilisateur est libre de créer sa propre recette en y ajoutant des ingrédients, des étapes, des tags, une description, un titre et optionnellement une image pour illustrer la recette. En option l'utilisateur peut indiquer des temps de préparation et de cuisson ainsi qu'un nombre de personnes donné pour la recette et ses quantités.

-- 

## Créer / Ajouter des listes de courses
Il est possible de générer automatiquement une liste de courses à partir d'une recette, ou d'ajouter les ingrédients d'une recette à une liste de courses existantes. Sur chaque liste de courses, vous avez la possibilité de modifier les quantités d'ingrédients, d'en ajouter ou d'en supprimer.

--

##  Rechercher des recettes à partir d'ingrédients
Un formulaire permet de rechercher des recettes à partir d'ingrédients. Il suffit d'ajouter autant d'ingredients que désiré, une liste de recettes comprenant un ou plusieurs de ces ingrédients vous sera proposée.

--

### Installation
- Veillez à installer Symfony et Composer au préalable
- Fork le depôt et initialisez-le selon les préconisations de Github
- Une fois le dépôt cloné localement, n'oubliez pas bien-sûr de créer un compte utilisateur dans votre base de données et de créer/configurer votre fichier .env.local à la racine de votre projet
- Chargez les librairies avec composer ( composer install )
- Crééz et jouez vos migrations ( php/bin console make:migration ) puis ( php bin/console doctrine:migrations:migrate )
- Jouez les fixtures pour avoir de base un set d'unités pour les ingrédients, des catégories, des tags etc. ainsi que les rôles utilisateur & admin indispensables à l'utilisation du site ( php bin/console doctrine:fixtures:load )
- A la racine du dossier "public" créer un dossier et nommez-le "uploads". Ce dernier contiendra les images que vos utilisateurs chargeront pour vos recettes
- N'oubliez pas d'attribuer des droits en lecture et écriture nécessaires sur certains dossiers
- Enjoy, il reste sûrement plein de bugs et/ou de choses à améliorer.

NB : N'hésitez pas à ouvrir une issue si vous avez la moindre question ou le moindre doute quant à l'installation/configuration du projet.
