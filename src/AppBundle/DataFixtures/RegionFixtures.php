<?php
/**
 * Created by PhpStorm.
 * User: ck
 * Date: 01/12/17
 * Time: 12:09
 */

// src/DataFixtures/AppFixtures.php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RegionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = array(
            array(700, "Alsace"),
            array(701, "Aquitaine"),
            array(702, "Auvergne"),
            array(703, "Basse-Normandie"),
            array(704, "Bourgogne"),
            array(705, "Bretagne"),
            array(706, "Centre"),
            array(707, "Champagne"),
            array(20, "Corse"),
            array(99712, "France Outre-Mer"),
            array(709, "Franche-Comté"),
            array(710, "Haute-Normandie"),
            array(711, "Ile-de-France"),
            array(712, "Languedoc-Roussillon"),
            array(713, "Limousin"),
            array(714, "Lorraine"),
            array(715, "Midi-Pyrénées"),
            array(716, "Nord-Pas-de-Calais"),
            array(720, "PACA"),
            array(717, "Pays de La Loire"),
            array(718, "Picardie"),
            array(719, "Poitou-Charentes"),
            array(721, "Rhône-Alpes")
        );

        foreach($data as $myEntity){
            $entity = new Region();
            $entity->setApecId($myEntity[0]);
            $entity->setName($myEntity[1]);
            $manager->persist($entity);
        }
        $manager->flush();
    }
}