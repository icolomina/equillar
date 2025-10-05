<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Persistence\Layers\Doctrine;

use App\Persistence\PersistorInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrinePersistor implements PersistorInterface
{
    public function __construct(
        protected readonly EntityManagerInterface $em,
    ) {
    }

    public function persist(array|object $entity): void
    {
        $entities = is_object($entity) ? [$entity] : $entity;
        foreach ($entities as $e) {
            $this->em->persist($e);
        }
    }

    public function persistAndFlush(array|object $entity): void
    {
        $entity = is_array($entity) ? $entity : [$entity];
        foreach ($entity as $e) {
            $this->em->persist($e);
        }

        $this->em->flush();
    }

    public function flush(): void
    {
        $this->em->flush();
    }

    public function refresh(object $object): void
    {
        $this->em->refresh($object);
    }

    /**
     * @return EntityManagerInterface
     */
    public function getLayerManager(): mixed
    {
        return $this->em;
    }
}
