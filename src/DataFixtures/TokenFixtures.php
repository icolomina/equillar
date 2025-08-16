<?php

namespace App\DataFixtures;

use App\Entity\Token;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TokenFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        $tokenUSDC = new Token();
        $tokenUSDC->setAddress('CBIELTK6YBZJU5UP2WWQEUCYKLPU6AUNZ2BQ4WWFEIE3USCIHMXQDAMA');
        $tokenUSDC->setDecimals(7);
        $tokenUSDC->setCode('USDC');
        $tokenUSDC->setName('Circle Dollar');
        $tokenUSDC->setCreatedAt(new \DateTimeImmutable());
        $tokenUSDC->setIssuer('Circle');
        $tokenUSDC->setEnabled(true);
        $tokenUSDC->setType('STABLE-COIN');
        $tokenUSDC->setLocale('en-US');
        $tokenUSDC->setReferencedCurrency('USD');

        $manager->persist($tokenUSDC);
        $manager->flush();
    }
}