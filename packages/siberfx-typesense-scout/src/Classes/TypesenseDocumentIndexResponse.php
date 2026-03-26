<?php

namespace Siberfx\Typesense\Classes;

/**
 * Class TypesenseDocumentIndexResponse.
 *
 * @date   02/10/2021
 *
 * @author Selim Görmüş <info@siberfx.com>
 */
class TypesenseDocumentIndexResponse
{
    public function __construct(private ?int $code, private bool $success, private ?string $error = null, private ?array $document = null) {}

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getDocument(): ?array
    {
        return $this->document;
    }
}
