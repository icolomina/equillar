<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Crypt\Aead\Service;

use App\Domain\Crypt\Aead\EntitySchemaBuilderInterface;

class EntitySchemaBuilderLocator 
{
    private EntitySchemaBuilderCollection $schemaBuilders;

    public function setSchemaBuilders( EntitySchemaBuilderCollection $schemaBuilders) 
    {    
        $this->schemaBuilders = $schemaBuilders;
    }

    public function getSchemaBuilder(string $entity, string $version): ?EntitySchemaBuilderInterface 
    {
        return $this->schemaBuilders->getSchemaBuilderVersion($entity, $version);
    }

    public function getLatestSchemaBuilder(string $entity): ?EntitySchemaBuilderInterface 
    {
        return $this->schemaBuilders->getLatestSchemaVersion($entity);
    }
    
}