<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Crypt\Aead\Service;

use App\Domain\Crypt\Aead\AeadCryptedValue;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EntityAeadEncryptor
{
    public function __construct(
        private readonly EntitySchemaBuilderLocator $schemaBuilderLocator,
        private readonly AeadEncryptor $aeadEncryptor,
        private readonly DenormalizerInterface $serializer
    ) {
    }

    public function encryptEntity(object $entity, string $plain): AeadCryptedValue
    {
        $schemaBuilder = $this->schemaBuilderLocator->getLatestSchemaBuilder($entity::class);
        if ($schemaBuilder === null) {
            throw new \RuntimeException('No schema builder found for entity: ' . $entity::class);
        }   
        $associatedData = $schemaBuilder->build($entity);

        return $this->aeadEncryptor->encryptMsg(
            $plain, 
            $associatedData, 
            $schemaBuilder->getEntityClass(),
            $schemaBuilder->getVersion()
        );
    }

    public function decryptEntity(object $entity, array|AeadCryptedValue $cryptedValue): string
    {
        $cryptedValue  = ($cryptedValue instanceof AeadCryptedValue) 
            ? $cryptedValue
            : $this->serializer->denormalize($cryptedValue, AeadCryptedValue::class)
        ;

        $schemaBuilder = $this->schemaBuilderLocator->getSchemaBuilder($cryptedValue->schema, $cryptedValue->version);
        if ($schemaBuilder === null) {
            throw new \RuntimeException('No schema builder found for entity: ' . $cryptedValue->schema . ' version: ' . $cryptedValue->version);
        }

        $associatedData = $schemaBuilder->build($entity);
        return $this->aeadEncryptor->decryptMsg(
            $cryptedValue,
            $associatedData
        );
    }
}