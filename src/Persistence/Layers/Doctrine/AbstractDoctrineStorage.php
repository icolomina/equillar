<?php

namespace App\Persistence\Layers\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractDoctrineStorage
{
    use PersistEntityTrait;
    public function __construct(
        protected readonly EntityManagerInterface $em
    ){}
}
