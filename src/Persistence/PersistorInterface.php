<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Persistence;

interface PersistorInterface
{
    public function persist(array|object $entity): void;

    public function persistAndFlush(array|object $entity): void;

    public function flush(): void;

    public function refresh(object $object): void;

    public function getLayerManager(): mixed;
}
