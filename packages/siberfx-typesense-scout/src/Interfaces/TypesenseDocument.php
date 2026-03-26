<?php

namespace Siberfx\Typesense\Interfaces;

/**
 * Interface TypesenseSearch
 */
interface TypesenseDocument
{
    public function typesenseQueryBy(): array;

    public function getCollectionSchema(): array;
}
