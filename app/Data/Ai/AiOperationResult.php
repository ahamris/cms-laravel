<?php

namespace App\Data\Ai;

final class AiOperationResult
{
    /**
     * @param  array<string, mixed>  $data
     */
    private function __construct(
        public readonly bool $success,
        public readonly ?string $error,
        public readonly array $data = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function success(array $data = []): self
    {
        return new self(true, null, $data);
    }

    public static function failure(string $error): self
    {
        return new self(false, $error);
    }

    /**
     * @return array{success: bool, error?: string, data?: array<string, mixed>}
     */
    public function toApiArray(): array
    {
        if (! $this->success) {
            return [
                'success' => false,
                'error' => $this->error ?? 'AI operation failed.',
            ];
        }

        return [
            'success' => true,
            'data' => $this->data,
        ];
    }
}
