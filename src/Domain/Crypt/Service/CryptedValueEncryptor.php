<?php

namespace App\Domain\Crypt\Service;

use App\Domain\Crypt\CryptedValue;
use Symfony\Component\Serializer\SerializerInterface;

class CryptedValueEncryptor
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly Encryptor $encryptor
    ){}

    public function getSecret(array|CryptedValue $cryptedValue): string
    {
        $privateKeyCryptedData = ($cryptedValue instanceof CryptedValue)
            ? $cryptedValue
            : $this->serializer->denormalize($cryptedValue, CryptedValue::class)
        ;
        
        return $this->encryptor->decryptMsg($privateKeyCryptedData->cipher, $privateKeyCryptedData->nonce);
    }
}
