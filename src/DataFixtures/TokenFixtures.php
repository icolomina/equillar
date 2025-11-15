<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
        $tokenUSDC->setIssuer('Centre.io');
        $tokenUSDC->setIssuerAddress('GBBD47IF6LWK7P7MDEVSCWR7DPUWV3NY3DTQEVFL4NAT4AQH3ZLLFLA5');
        $tokenUSDC->setIssuerSite('https://www.usdc.com/');
        $tokenUSDC->setEnabled(true);
        $tokenUSDC->setType('STABLE-COIN');
        $tokenUSDC->setLocale('en-US');
        $tokenUSDC->setReferencedCurrency('USD');

        $tokenEURC = new Token();
        $tokenEURC->setAddress('CCUUDM434BMZMYWYDITHFXHDMIVTGGD6T2I5UKNX5BSLXLW7HVR4MCGZ');
        $tokenEURC->setDecimals(7);
        $tokenEURC->setCode('EURC');
        $tokenEURC->setName('Circle Euro');
        $tokenEURC->setCreatedAt(new \DateTimeImmutable());
        $tokenEURC->setIssuer('Circle.com');
        $tokenEURC->setIssuerAddress('GB3Q6QDZYTHWT7E5PVS3W7FUT5GVAFC5KSZFFLPU25GO7VTC3NM2ZTVO');
        $tokenEURC->setIssuerSite('https://www.circle.com/');
        $tokenEURC->setEnabled(true);
        $tokenEURC->setType('STABLE-COIN');
        $tokenEURC->setLocale('es-ES');
        $tokenEURC->setReferencedCurrency('EUR');

        $manager->persist($tokenUSDC);
        $manager->persist($tokenEURC);
        $manager->flush();
    }
}
