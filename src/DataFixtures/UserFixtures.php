<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $userCompany1 = new User();
        $userCompany1->setEmail('greencycle@company.com');
        $userCompany1->setName('GreenCycle Inc');
        $userCompany1->setCreatedAt(new \DateTimeImmutable());
        $userCompany1->setPassword($this->passwordHasher->hashPassword($userCompany1, 'company1'));
        $userCompany1->setRoles([User::ROLE_FINANCIAL_ENTITY]);

        $userCompany2 = new User();
        $userCompany2->setEmail('medtech@company.com');
        $userCompany2->setName('MedTech Solutions Ltd');
        $userCompany2->setCreatedAt(new \DateTimeImmutable());
        $userCompany2->setPassword($this->passwordHasher->hashPassword($userCompany2, 'company2'));
        $userCompany2->setRoles([User::ROLE_FINANCIAL_ENTITY]);

        $userSaver1 = new User();
        $userSaver1->setEmail('peter.parker@investor.com');
        $userSaver1->setName('Peter Parker');
        $userSaver1->setCreatedAt(new \DateTimeImmutable());
        $userSaver1->setPassword($this->passwordHasher->hashPassword($userSaver1, 'investor1'));
        $userSaver1->setRoles([User::ROLE_SAVER]);

        $userSaver2 = new User();
        $userSaver2->setEmail('clark.kent@investor.com');
        $userSaver2->setName('Clark Kent');
        $userSaver2->setCreatedAt(new \DateTimeImmutable());
        $userSaver2->setPassword($this->passwordHasher->hashPassword($userSaver2, 'investor2'));
        $userSaver2->setRoles([User::ROLE_SAVER]);

        $userSaver3 = new User();
        $userSaver3->setEmail('diana.prince@investor.com');
        $userSaver3->setName('Diana Prince');
        $userSaver3->setCreatedAt(new \DateTimeImmutable());
        $userSaver3->setPassword($this->passwordHasher->hashPassword($userSaver3, 'investor3'));
        $userSaver3->setRoles([User::ROLE_SAVER]);

        $userAdmin = new User();
        $userAdmin->setEmail('support@admin.com');
        $userAdmin->setName('Support');
        $userAdmin->setCreatedAt(new \DateTimeImmutable());
        $userAdmin->setPassword($this->passwordHasher->hashPassword($userAdmin, 'admin'));
        $userAdmin->setRoles([User::ROLE_ADMIN]);

        $manager->persist($userCompany1);
        $manager->persist($userCompany2);
        $manager->persist($userSaver1);
        $manager->persist($userSaver2);
        $manager->persist($userSaver3);
        $manager->persist($userAdmin);

        $manager->flush();
    }
}
