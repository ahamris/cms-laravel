<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class BlogContentWriterAgent implements Agent, Conversational, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        protected string $tone = 'professional',
        protected string $lengthGuide = '1500-2000 words',
    ) {}

    public function instructions(): Stringable|string
    {
        return <<<TXT
You are an expert content writer specializing in SEO-optimized blog posts.

Requirements:
1. Write a compelling, well-structured blog post
2. Use SEO best practices (natural keyword usage, proper headings)
3. Include an engaging introduction and conclusion
4. Use clear headings (H2, H3) with HTML tags
5. Tone: {$this->tone}
6. Target length: {$this->lengthGuide}
7. Format the body as clean HTML (no markdown)

Respond only with structured fields matching the schema (no extra keys).
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
            'title' => $schema->string()->max(200)->required(),
            'short_body' => $schema->string()->max(500)->required(),
            'long_body' => $schema->string()->required(),
            'meta_title' => $schema->string()->max(60)->required(),
            'meta_description' => $schema->string()->max(160)->required(),
            'meta_keywords' => $schema->string()->required(),
        ];
    }
}
