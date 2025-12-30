<?php

namespace App\Domain\Crypt\Aead;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.crypt.aead_schema')]
interface EntitySchemaBuilderInterface
{
    public function build(object $object): string;
    public function getEntityClass(): string;
    public function getVersion(): string;
}