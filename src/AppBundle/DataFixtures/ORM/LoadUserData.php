<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $davi = new User();
        $davi->setName('Davi Vidal');
        $davi->setLogin('davi');
        $davi->encryptPassword('teste');

        $manager->persist($davi);

        $juca = new User();
        $juca->setName('Juca');
        $juca->setLogin('juca');
        $juca->encryptPassword('teste');

        $manager->persist($juca);

        $manager->flush();
    }
}
