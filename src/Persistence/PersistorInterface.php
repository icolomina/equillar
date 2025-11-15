<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Persistence;

interface PersistorInterface
{
    public function persist(array|object $entity): void;

    public function persistAndFlush(array|object $entity): void;

    public function flush(): void;

    public function refresh(object $object): void;

    public function getLayerManager(): mixed;
}
