<?php

namespace App\Persistence\Layers\Doctrine;

trait PersistEntityTrait
{
    public function persist(array|object $entity): void
    {
        $entities = is_object($entity) ? [$entity] : $entity;
        foreach($entities as $e) {
            $this->em->persst($e);
        }
    }

    public function persistAndFlush(array|object $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
