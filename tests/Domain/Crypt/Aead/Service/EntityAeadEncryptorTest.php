<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Tests\Domain\Crypt\Aead\Service;

use App\Domain\Crypt\Aead\Service\EntityAeadEncryptor;
use App\Entity\SystemWallet;
use App\Tests\EntityGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EntityAeadEncryptorTest extends KernelTestCase
{
    private EntityAeadEncryptor $entityAeadEncryptor;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        
        $this->entityAeadEncryptor = $container->get('test.App\Domain\Crypt\Aead\Service\EntityAeadEncryptor');
    }

    public function testEncryptEntityWithSystemWallet(): void
    {
        $systemWallet = EntityGenerator::createSystemWallet();
        $plaintext = 'secret_private_key_data';

        $encrypted = $this->entityAeadEncryptor->encryptEntity($systemWallet, $plaintext);

        $this->assertNotEmpty($encrypted->ciphertext);
        $this->assertNotEmpty($encrypted->nonce);
        $this->assertEquals(SystemWallet::class, $encrypted->schema);
        $this->assertEquals('v1', $encrypted->version);
        $this->assertNotEmpty($encrypted->keyId);
        $this->assertNotEmpty($encrypted->context);
        $this->assertIsInt($encrypted->subkeyId);
    }

    public function testDecryptEntityWithAeadCryptedValue(): void
    {
        $systemWallet = EntityGenerator::createSystemWallet();
        $plaintext = 'secret_private_key_data';

        $encrypted = $this->entityAeadEncryptor->encryptEntity($systemWallet, $plaintext);
        $decrypted = $this->entityAeadEncryptor->decryptEntity($systemWallet, $encrypted);

        $this->assertEquals($plaintext, $decrypted);
    }

    public function testDecryptEntityWithArray(): void
    {
        $systemWallet = EntityGenerator::createSystemWallet();
        $plaintext = 'secret_private_key_data';

        $encrypted = $this->entityAeadEncryptor->encryptEntity($systemWallet, $plaintext);
        
        // Serialize to array
        $encryptedArray = [
            'ciphertext' => $encrypted->ciphertext,
            'nonce' => $encrypted->nonce,
            'schema' => $encrypted->schema,
            'version' => $encrypted->version,
            'engine' => $encrypted->engine,
            'keyId' => $encrypted->keyId,
            'context' => $encrypted->context,
            'subkeyId' => $encrypted->subkeyId,
        ];

        $decrypted = $this->entityAeadEncryptor->decryptEntity($systemWallet, $encryptedArray);

        $this->assertEquals($plaintext, $decrypted);
    }

    public function testEncryptEntityThrowsExceptionWhenSchemaBuilderNotFound(): void
    {
        $unsupportedEntity = new class {
            public function getId(): int
            {
                return 1;
            }
        };

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No schema builder found for entity:');

        $this->entityAeadEncryptor->encryptEntity($unsupportedEntity, 'test');
    }

    public function testDecryptEntityThrowsExceptionWhenSchemaBuilderNotFoundForVersion(): void
    {
        $systemWallet = EntityGenerator::createSystemWallet();
        $plaintext = 'secret_data';

        $encrypted = $this->entityAeadEncryptor->encryptEntity($systemWallet, $plaintext);

        // Create a crypted value with non-existent version
        $invalidEncrypted = [
            'ciphertext' => $encrypted->ciphertext,
            'nonce' => $encrypted->nonce,
            'schema' => $encrypted->schema,
            'version' => 'v999',
            'engine' => $encrypted->engine,
            'keyId' => $encrypted->keyId,
            'context' => $encrypted->context,
            'subkeyId' => $encrypted->subkeyId,
        ];

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No schema builder found for entity:');

        $this->entityAeadEncryptor->decryptEntity($systemWallet, $invalidEncrypted);
    }

    public function testDecryptionFailsWithDifferentEntity(): void
    {
        $systemWallet1 = EntityGenerator::createSystemWallet();
        $systemWallet2 = EntityGenerator::createSystemWallet();
        $systemWallet2->setCreatedAt(new \DateTimeImmutable('-1 day')); // Change a property to alter additional data
        $plaintext = 'secret_data';

        // Encrypt with first wallet
        $encrypted = $this->entityAeadEncryptor->encryptEntity($systemWallet1, $plaintext);

        // Try to decrypt with different wallet (different additional data)
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Decryption or authentication failed');

        $this->entityAeadEncryptor->decryptEntity($systemWallet2, $encrypted);
    }

    public function testSchemaBuilderGeneratesConsistentAdditionalData(): void
    {
        $systemWallet = EntityGenerator::createSystemWallet();
        $plaintext = 'secret_data';

        // Encrypt twice with same entity
        $encrypted1 = $this->entityAeadEncryptor->encryptEntity($systemWallet, $plaintext);
        $encrypted2 = $this->entityAeadEncryptor->encryptEntity($systemWallet, $plaintext);

        // SubkeyId should be the same (derived from same additional data)
        $this->assertEquals($encrypted1->subkeyId, $encrypted2->subkeyId);
        
        // But ciphertext and nonce should differ (random nonce and context)
        $this->assertNotEquals($encrypted1->ciphertext, $encrypted2->ciphertext);
        $this->assertNotEquals($encrypted1->nonce, $encrypted2->nonce);
        $this->assertNotEquals($encrypted1->context, $encrypted2->context);
    }
}
