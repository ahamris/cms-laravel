<?php

namespace App\Ai\Agents;

use App\Ai\Tools\LookupCrmContactTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class CrmSupportAgent implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;

    public function __construct(
        protected ?int $contactId = null,
        protected string $tone = 'professional',
        protected string $language = 'nl',
    ) {}

    public function instructions(): Stringable|string
    {
        $toolHint = $this->contactId
            ? 'You may call LookupCrmContactTool once to load read-only CRM context for this customer.'
            : 'No CRM contact tool is available; rely only on the thread text.';

        return <<<TXT
You assist support agents handling customer threads.

{$toolHint}

Produce:
- summary: short TL;DR of the thread for handoff
- suggested_reply: the actual reply to send the customer (plain text, empathetic, solution-oriented)
- suggested_status: one of open, in_progress, waiting, resolved, closed (best next workflow status)
- risk_flags: short strings highlighting risks (e.g. churn, legal, urgent) — empty array if none

Tone: {$this->tone}. Language: {$this->language}.

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

    /**
     * @return Tool[]
     */
    public function tools(): iterable
    {
        if ($this->contactId === null || $this->contactId < 1) {
            return [];
        }

        return [new LookupCrmContactTool($this->contactId)];
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'summary' => $schema->string()->required(),
            'suggested_reply' => $schema->string()->required(),
            'suggested_status' => $schema->string()->required(),
            'risk_flags' => $schema->array()->items($schema->string())->default([]),
        ];
    }
}
