<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Tests\Domain\Crypt\Aead\Service;

use App\Domain\Crypt\Aead\AeadCryptedValue;
use App\Domain\Crypt\Aead\Service\AeadEncryptor;
use SodiumException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AeadEncryptorTest extends KernelTestCase
{
    private AeadEncryptor $aeadEncryptor;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        
        $this->aeadEncryptor = $container->get('test.App\Domain\Crypt\Aead\Service\AeadEncryptor');
    }

    public function testEncryptAndDecryptWithDerivedKey(): void
    {
        $plaintext = 'This is a secret message for testing AEAD encryption';
        $schema = 'systemwallet';
        $version = 'v1'; 
        
        $additionalData = sprintf(
            '%d|testnet|GXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
            time()
        );

        $encrypted = $this->aeadEncryptor->encryptMsg(
            $plaintext,
            $additionalData,
            $schema,
            $version
        );

        $this->assertNotEmpty($encrypted->ciphertext);
        $this->assertNotEmpty($encrypted->nonce);
        $this->assertEquals($schema, $encrypted->schema);
        $this->assertEquals($version, $encrypted->version);
        $this->assertNotEmpty($encrypted->keyId);
        $this->assertNotEmpty($encrypted->context);
        $this->assertIsInt($encrypted->subkeyId);
        $this->assertGreaterThan(0, $encrypted->subkeyId);

        $decrypted = $this->aeadEncryptor->decryptMsg($encrypted, $additionalData);
        
        $this->assertEquals($plaintext, $decrypted);
    }

    public function testDecryptionFailsWithTamperedCiphertext(): void
    {
        $plaintext = 'Secret data';
        $schema = 'systemwallet';
        $version = 'v1'; 
        $additionalData = 'metadata|test|value';

        $encrypted = $this->aeadEncryptor->encryptMsg(
            $plaintext,
            $additionalData,
            $schema,
            $version
        );

        $tamperedCiphertext = base64_encode(
            substr(base64_decode($encrypted->ciphertext, true), 0, -1) . 'X'
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Decryption or authentication failed');

        $this->aeadEncryptor->decryptMsg(new AeadCryptedValue(
            $tamperedCiphertext,
            $encrypted->nonce,
            $encrypted->schema,
            $encrypted->version,
            $encrypted->engine,
            $encrypted->keyId,
            $encrypted->context,
            $encrypted->subkeyId
        ), $additionalData);
    }

    public function testDecryptionFailsWithTamperedAdditionalData(): void
    {
        $plaintext = 'Secret data';
        $schema = 'test';
        $version = 'v1';
        $additionalData = 'metadata|test|value';

        $encrypted = $this->aeadEncryptor->encryptMsg(
            $plaintext,
            $additionalData,
            $schema,
            $version
        );

        $tamperedAdditionalData = 'metadata|test|CHANGED';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Decryption or authentication failed');

        $this->aeadEncryptor->decryptMsg($encrypted,$tamperedAdditionalData);
    }

    public function testDecryptionFailsWithWrongContext(): void
    {
        $plaintext = 'Secret data';
        $schema = 'test';
        $version = 'v1';
        $additionalData = 'metadata|test|value';

        $encrypted = $this->aeadEncryptor->encryptMsg(
            $plaintext,
            $additionalData,
            $schema,
            $version
        );

        $wrongContext = 'wrongctx';

        $this->expectException(SodiumException::class);

        $this->aeadEncryptor->decryptMsg(
            new AeadCryptedValue(
                $encrypted->ciphertext,
                $encrypted->nonce,
                $encrypted->schema,
                $encrypted->version,
                $encrypted->engine,
                $encrypted->keyId,
                $wrongContext,
                $encrypted->subkeyId
            ),           
            $additionalData
        );
    }

    public function testDecryptionFailsWithWrongSubkeyId(): void
    {
        $plaintext = 'Secret data';
        $schema = 'test';
        $version = 'v1';
        $additionalData = 'metadata|test|value';

        $encrypted = $this->aeadEncryptor->encryptMsg(
            $plaintext,
            $additionalData,
            $schema,
            $version
        );

        $wrongSubkeyId = $encrypted->subkeyId + 1;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Decryption or authentication failed');

        $this->aeadEncryptor->decryptMsg(
            new AeadCryptedValue(
                $encrypted->ciphertext,
                $encrypted->nonce,
                $encrypted->schema,
                $encrypted->version,
                $encrypted->engine,
                $encrypted->keyId,
                $encrypted->context,
                $wrongSubkeyId      
            ),
            $additionalData
        );
    }

    public function testSameAdditionalDataProducesSameSubkeyId(): void
    {
        $plaintext = 'Secret data';
        $schema = 'test';
        $version = 'v1';
        $additionalData = 'metadata|test|consistent';

        $encrypted1 = $this->aeadEncryptor->encryptMsg(
            $plaintext,
            $additionalData,
            $schema,
            $version
        );

        $encrypted2 = $this->aeadEncryptor->encryptMsg(
            $plaintext,
            $additionalData,
            $schema,
            $version
        );

        $this->assertEquals($encrypted1->subkeyId, $encrypted2->subkeyId);
        $this->assertNotEquals($encrypted1->ciphertext, $encrypted2->ciphertext);
        $this->assertNotEquals($encrypted1->nonce, $encrypted2->nonce);
    }

    public function testDifferentAdditionalDataProducesDifferentSubkeyId(): void
    {
        $plaintext = 'Secret data';
        $schema = 'test';
        $version = 'v1'; 
        $additionalData1 = 'metadata|test|value1';
        $additionalData2 = 'metadata|test|value2';

        $encrypted1 = $this->aeadEncryptor->encryptMsg(
            $plaintext,
            $additionalData1,
            $schema,
            $version
        );

        $encrypted2 = $this->aeadEncryptor->encryptMsg(
            $plaintext,
            $additionalData2,
            $schema,
            $version
        );

        $this->assertNotEquals($encrypted1->subkeyId, $encrypted2->subkeyId);
    }

    public function testGetCurrentKeyId(): void
    {
        $keyId = $this->aeadEncryptor->getCurrentKeyId();
        $this->assertNotEmpty($keyId);
        $this->assertIsString($keyId);
    }
}
