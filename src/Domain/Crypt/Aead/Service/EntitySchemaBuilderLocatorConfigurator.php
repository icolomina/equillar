<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Crypt\Aead\Service;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class EntitySchemaBuilderLocatorConfigurator 
{
    private readonly array $handlers;
    public function __construct(
        #[AutowireIterator('app.crypt.aead_schema')]
        iterable $handlers
    ) {
        $this->handlers = iterator_to_array($handlers);
    }

    public function configure(EntitySchemaBuilderLocator $entitySchemaBuilderLocator) {
        
        $collection = new EntitySchemaBuilderCollection();
        foreach($this->handlers as $sch) {
            
            $collection->addSchemaBuilder($sch);
        }

        $entitySchemaBuilderLocator->setSchemaBuilders($collection);
    }
    
}