<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Crypt\Aead\Service;

use App\Domain\Crypt\Aead\EntitySchemaBuilderInterface;

class EntitySchemaBuilderCollection 
{

    private array $schemaBuilders = [];

    public function addSchemaBuilder(EntitySchemaBuilderInterface $schema): void
    {
        if (!isset($this->schemaBuilders[$schema->getEntityClass()])) {
            $this->schemaBuilders[$schema->getEntityClass()] = [];
        }

        $this->schemaBuilders[$schema->getEntityClass()][$schema->getVersion()] = $schema;
    }

    public function getSchemaBuilders(string $entity): array
    {
        return $this->schemaBuilders[$entity] ?? [];
    }

    public function getSchemaBuilderVersion(string $entity, string $version): ?EntitySchemaBuilderInterface
    {
        if (!isset($this->schemaBuilders[$entity])) {
            return null;
        }

        return $this->schemaBuilders[$entity][$version] ?? null;
    }

    public function getLatestSchemaVersion(string $entity): ?EntitySchemaBuilderInterface 
    {
        if (!isset($this->schemaBuilders[$entity])) {
            return null;
        }

        $versions = $this->schemaBuilders[$entity];

        uksort($versions, fn($a, $b) => version_compare($b, $a)); // Descendente

        return reset($versions);
    }
}