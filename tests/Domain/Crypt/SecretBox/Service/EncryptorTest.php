<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Tests\Domain\Crypt\SecretBox\Service;

use App\Domain\Crypt\SecretBox\Service\Encryptor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EncryptorTest extends KernelTestCase
{
    private Encryptor $encryptor;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        
        $this->encryptor = $container->get('test.App\Domain\Crypt\SecretBox\Service\Encryptor');
    }

    public function testEncryptAndDecrypt(): void
    {
        $plaintext = 'This is a secret message for testing SecretBox encryption';

        $encrypted = $this->encryptor->encryptMsg($plaintext);

        $this->assertNotEmpty($encrypted->cipher);
        $this->assertNotEmpty($encrypted->nonce);
        $this->assertEquals('secret_box', $encrypted->engine);

        $decrypted = $this->encryptor->decryptMsg($encrypted->cipher, $encrypted->nonce);

        $this->assertEquals($plaintext, $decrypted);
    }

    public function testDecryptionFailsWithTamperedCiphertext(): void
    {
        $plaintext = 'Secret data';

        $encrypted = $this->encryptor->encryptMsg($plaintext);

        $tamperedCipher = base64_encode(
            substr(base64_decode($encrypted->cipher, true), 0, -1) . 'X'
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Decryption failed');

        $this->encryptor->decryptMsg($tamperedCipher, $encrypted->nonce);
    }

    public function testDecryptionFailsWithWrongNonce(): void
    {
        $plaintext = 'Secret data';

        $encrypted = $this->encryptor->encryptMsg($plaintext);

        $wrongNonce = base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Decryption failed');

        $this->encryptor->decryptMsg($encrypted->cipher, $wrongNonce);
    }

    public function testDecryptionFailsWithInvalidBase64(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base64 input');

        $this->encryptor->decryptMsg('not-valid-base64!!!', 'also-not-valid!!!');
    }

    public function testDecryptionFailsWithInvalidNonceLength(): void
    {
        $plaintext = 'Secret data';

        $encrypted = $this->encryptor->encryptMsg($plaintext);

        // Nonce with wrong length
        $shortNonce = base64_encode('short');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid nonce length');

        $this->encryptor->decryptMsg($encrypted->cipher, $shortNonce);
    }

    public function testMultipleEncryptionsProduceDifferentCiphertexts(): void
    {
        $plaintext = 'Secret data';

        $encrypted1 = $this->encryptor->encryptMsg($plaintext);
        $encrypted2 = $this->encryptor->encryptMsg($plaintext);

        // Different nonces should produce different ciphertexts
        $this->assertNotEquals($encrypted1->nonce, $encrypted2->nonce);
        $this->assertNotEquals($encrypted1->cipher, $encrypted2->cipher);

        // But both should decrypt to the same plaintext
        $decrypted1 = $this->encryptor->decryptMsg($encrypted1->cipher, $encrypted1->nonce);
        $decrypted2 = $this->encryptor->decryptMsg($encrypted2->cipher, $encrypted2->nonce);

        $this->assertEquals($plaintext, $decrypted1);
        $this->assertEquals($plaintext, $decrypted2);
    }

    public function testEncryptEmptyString(): void
    {
        $plaintext = '';

        $encrypted = $this->encryptor->encryptMsg($plaintext);
        $decrypted = $this->encryptor->decryptMsg($encrypted->cipher, $encrypted->nonce);

        $this->assertEquals($plaintext, $decrypted);
    }

    public function testEncryptLongString(): void
    {
        $plaintext = str_repeat('This is a very long secret message. ', 100);

        $encrypted = $this->encryptor->encryptMsg($plaintext);
        $decrypted = $this->encryptor->decryptMsg($encrypted->cipher, $encrypted->nonce);

        $this->assertEquals($plaintext, $decrypted);
    }
}
