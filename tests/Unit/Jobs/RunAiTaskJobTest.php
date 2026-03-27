<?php

use App\Jobs\RunAiTaskJob;
use App\Models\AiTask;
use App\Services\AIService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('marks ai task completed and stores result', function (): void {
    $task = AiTask::query()->create([
        'task_type' => 'generate_page',
        'status' => AiTask::STATUS_PENDING,
        'payload' => [
            'topic' => 'Test page',
            'tone' => 'professional',
            'language' => 'en',
            'block_types' => ['hero'],
        ],
    ]);

    $service = new class extends AIService
    {
        public function generatePageBlocks(string $topic, string $tone = 'professional', string $language = 'nl', array $blockTypes = ['hero', 'text', 'cta']): array
        {
            return [
                'success' => true,
                'provider' => 'gemini',
                'model' => 'gemini-2.0-flash',
                'blocks' => [['type' => 'hero']],
            ];
        }
    };

    $job = new RunAiTaskJob($task->id);
    $job->handle($service);

    $task->refresh();
    expect($task->status)->toBe(AiTask::STATUS_COMPLETED);
    expect($task->result)->toBeArray();
    expect($task->provider)->toBe('gemini');
});

it('marks ai task failed when ai operation fails', function (): void {
    $task = AiTask::query()->create([
        'task_type' => 'content_plan',
        'status' => AiTask::STATUS_PENDING,
        'payload' => [
            'topic' => 'Test plan',
            'items' => 3,
            'language' => 'en',
        ],
    ]);

    $service = new class extends AIService
    {
        public function generateContentPlan(string $topic, int $items = 5, string $language = 'nl'): array
        {
            return [
                'success' => false,
                'error' => 'Provider unavailable',
            ];
        }
    };

    $job = new RunAiTaskJob($task->id);
    $job->handle($service);

    $task->refresh();
    expect($task->status)->toBe(AiTask::STATUS_FAILED);
    expect($task->error_message)->toContain('Provider unavailable');
});
