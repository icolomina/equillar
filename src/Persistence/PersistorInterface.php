<?php

namespace App\Persistence;

interface PersistorInterface
{
    public function persist(array|object $entity): void;
    public function persistAndFlush(array|object $entity): void;
    public function flush(): void;
}
