<?php

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