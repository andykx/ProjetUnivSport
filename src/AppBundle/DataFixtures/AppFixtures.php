<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Etudiant;
use AppBundle\Entity\Sport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $etudiants=[];
        // create 3 etudiants! Bam!
        for ($i = 0; $i < 10; $i++) {
            $etudiant = new Etudiant();
            $etudiant->setNomEtu('NomEtu '.$i);
            $etudiant->setPrenomEtu('PrenomEtu '.$i);
            $ch = <<<'MARKDOWN'
Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor
incididunt ut labore et **dolore magna aliqua**: Duis aute irure dolor in
reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
deserunt mollit anim id est laborum.

Praesent id fermentum lorem. Ut est lorem, fringilla at accumsan nec, euismod at
nunc. Aenean mattis sollicitudin mattis. Nullam pulvinar vestibulum bibendum.
Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
himenaeos. Fusce nulla purus, gravida ac interdum ut, blandit eget ex. Duis a
luctus dolor.

Integer auctor massa maximus nulla scelerisque accumsan. *Aliquam ac malesuada*
ex. Pellentesque tortor magna, vulputate eu vulputate ut, venenatis ac lectus.
Praesent ut lacinia sem. Mauris a lectus eget felis mollis feugiat. Quisque
efficitur, mi ut semper pulvinar, urna urna blandit massa, eget tincidunt augue
nulla vitae est.

MARKDOWN;

            $manager->persist($etudiant);
            $manager->flush();
            $etudiants[] = $etudiant;
        }

        foreach ($etudiants as $etudiant){
            for ($i = 0; $i < mt_rand(3, 7); $i++) {
                $sport = new Sport();
                $sport->setLibelle('Libelle '.$i);
                $manager->persist($etudiant);
            }
            $manager->flush();
        }
    }
}
