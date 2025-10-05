<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Domain\Crypt\Service;

use App\Domain\Crypt\CryptedValue;
use Symfony\Component\Serializer\SerializerInterface;

class CryptedValueEncryptor
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly Encryptor $encryptor,
    ) {
    }

    public function getSecret(array|CryptedValue $cryptedValue): string
    {
        $privateKeyCryptedData = ($cryptedValue instanceof CryptedValue)
            ? $cryptedValue
            : $this->serializer->denormalize($cryptedValue, CryptedValue::class)
        ;

        return $this->encryptor->decryptMsg($privateKeyCryptedData->cipher, $privateKeyCryptedData->nonce);
    }
}
