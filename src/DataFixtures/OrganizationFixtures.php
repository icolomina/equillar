<?php

namespace App\DataFixtures;

use App\Entity\Organization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrganizationFixtures extends Fixture
{
    public const ORG_GREENCYCLE = 'greencycle';
    public const ORG_MEDTECH    = 'medtech';

    public function load(ObjectManager $manager): void
    {
        $greenCycle = new Organization();
        $greenCycle->setName('GreenCycle Ltd');
        $greenCycle->setIdentifier('A-558941003');
        $greenCycle->setCreatedAt(new \DateTimeImmutable());

        $medtech = new Organization();
        $medtech->setName('MedTech Solutions Ltd');
        $medtech->setIdentifier('B-441003987');
        $medtech->setCreatedAt(new \DateTimeImmutable());

        $manager->persist($greenCycle);
        $manager->persist($medtech);
        $manager->flush();

        $this->addReference(self::ORG_MEDTECH, $medtech);
        $this->addReference(self::ORG_GREENCYCLE, $greenCycle);

    }
}
