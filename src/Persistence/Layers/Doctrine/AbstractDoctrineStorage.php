<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Persistence\Layers\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractDoctrineStorage
{
    public function __construct(
        protected readonly EntityManagerInterface $em,
    ) {
    }
}
