<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class PlanBlogContentAgent implements Agent, Conversational, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        protected string $tone,
        protected string $audience,
        protected string $primaryKeyword,
    ) {}

    public function instructions(): Stringable|string
    {
        return <<<TXT
You are an expert content writer specializing in SEO-optimized blog posts.

Requirements:
1. Write a compelling, well-structured blog post
2. Use SEO best practices (natural keyword usage, proper headings, etc.)
3. Include an engaging introduction and conclusion
4. Use clear headings (H2, H3) to structure the content in HTML
5. Tone: {$this->tone}
6. Target audience: {$this->audience}
7. Primary keyword: {$this->primaryKeyword}
8. Aim for roughly 1500-2500 words in the body

Map "excerpt" to a 150-200 word summary. Map "body" to full HTML content.

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
            'excerpt' => $schema->string()->required(),
            'body' => $schema->string()->required(),
        ];
    }
}
