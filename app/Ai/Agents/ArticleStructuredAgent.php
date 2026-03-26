<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class ArticleStructuredAgent implements Agent, Conversational, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        protected string $type = 'article',
        protected ?string $category = null,
        protected string $tone = 'informative',
        protected string $language = 'nl',
        protected int $length = 1000,
    ) {}

    public function instructions(): Stringable|string
    {
        $categoryLine = $this->category ? "Category context: {$this->category}. " : '';

        return <<<TXT
You are a professional content writer. Generate a blog article as structured data.

Type: {$this->type}. {$categoryLine}
Tone: {$this->tone}. Language: {$this->language}. Target length: ~{$this->length} words.

slug must be kebab-case. secondary_keywords is an array of short keyword strings.

Respond only with structured fields matching the schema.
TXT;
    }

    /**
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()->required(),
            'slug' => $schema->string()->required(),
            'short_body' => $schema->string()->required(),
            'long_body' => $schema->string()->required(),
            'meta_title' => $schema->string()->max(60)->required(),
            'meta_description' => $schema->string()->max(155)->required(),
            'primary_keyword' => $schema->string()->required(),
            'secondary_keywords' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
