<?php

namespace App\Persistence;

interface PersistorInterface
{
    public function persist(array|object $entity): void;
    public function persistAndFlush(array|object $entity): void;
    public function flush(): void;
    public function refresh(object $object): void;

    public function getLayerManager(): mixed;
}
