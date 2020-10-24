<?php

namespace App\DataFixtures;

use App\Entity\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $unit = new Unit;
        $unit->setName('unité');
        $manager->persist($unit);
        $manager->flush();

        $mg = new Unit;
        $mg->setName('mg');
        $manager->persist($mg);
        $manager->flush();

        $g = new Unit;
        $g->setName('g');
        $manager->persist($g);
        $manager->flush();

        $kg = new Unit;
        $kg->setName('kg');
        $manager->persist($kg);
        $manager->flush();

        $ml = new Unit;
        $ml->setName('ml');
        $manager->persist($ml);
        $manager->flush();

        $dl = new Unit;
        $dl->setName('dl');
        $manager->persist($dl);
        $manager->flush();

        $cl = new Unit;
        $cl->setName('cl');
        $manager->persist($cl);
        $manager->flush();

        $l = new Unit;
        $l->setName('Litre');
        $manager->persist($l);
        $manager->flush();

        $pincee = new Unit;
        $pincee->setName('pincée');
        $manager->persist($pincee);
        $manager->flush();

        $cf = new Unit;
        $cf->setName('cuillère à café');
        $manager->persist($cf);
        $manager->flush();

        $cs = new Unit;
        $cs->setName('cuillère à soupe');
        $manager->persist($cs);
        $manager->flush();

        $tasse = new Unit;
        $tasse->setName('tasse');
        $manager->persist($tasse);
        $manager->flush();        
    }
}
