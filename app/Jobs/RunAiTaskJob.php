<?php

namespace App\Jobs;

use App\Models\AiTask;
use App\Services\AIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RunAiTaskJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $taskId
    ) {}

    public function handle(AIService $aiService): void
    {
        /** @var AiTask|null $task */
        $task = AiTask::query()->find($this->taskId);
        if (! $task || $task->status !== AiTask::STATUS_PENDING) {
            return;
        }

        $task->update([
            'status' => AiTask::STATUS_RUNNING,
            'started_at' => now(),
        ]);

        $started = microtime(true);
        try {
            $payload = is_array($task->payload) ? $task->payload : [];
            $result = match ($task->task_type) {
                'generate_page' => $aiService->generatePageBlocks(
                    (string) ($payload['topic'] ?? ''),
                    (string) ($payload['tone'] ?? 'professional'),
                    (string) ($payload['language'] ?? 'nl'),
                    is_array($payload['block_types'] ?? null) ? $payload['block_types'] : ['hero', 'text', 'cta'],
                ),
                'generate_article' => $aiService->generateArticle(
                    (string) ($payload['topic'] ?? ''),
                    (string) ($payload['type'] ?? 'article'),
                    $payload['category'] ?? null,
                    (string) ($payload['tone'] ?? 'informative'),
                    (string) ($payload['language'] ?? 'nl'),
                    (int) ($payload['length'] ?? 1000),
                ),
                'optimize_seo' => $aiService->optimizeSEO((string) ($payload['content'] ?? '')),
                'content_plan' => $aiService->generateContentPlan(
                    (string) ($payload['topic'] ?? ''),
                    (int) ($payload['items'] ?? 5),
                    (string) ($payload['language'] ?? 'nl'),
                ),
                'draft_reply' => [
                    'success' => true,
                    'draft' => $aiService->draftReply(
                        (string) ($payload['message'] ?? ''),
                        (string) ($payload['tone'] ?? 'professional'),
                        (string) ($payload['language'] ?? 'nl'),
                        isset($payload['contact_id']) ? (int) $payload['contact_id'] : null,
                    ),
                ],
                default => ['success' => false, 'error' => 'Unknown task type.'],
            };

            $duration = (int) round((microtime(true) - $started) * 1000);
            $success = (bool) ($result['success'] ?? false);

            $task->update([
                'status' => $success ? AiTask::STATUS_COMPLETED : AiTask::STATUS_FAILED,
                'result' => $success ? $result : null,
                'error_message' => $success ? null : (string) ($result['error'] ?? 'Task failed.'),
                'duration_ms' => $duration,
                'provider' => (string) ($result['provider'] ?? ''),
                'model' => (string) ($result['model'] ?? ''),
                'completed_at' => now(),
            ]);
        } catch (\Throwable $e) {
            $task->update([
                'status' => AiTask::STATUS_FAILED,
                'error_message' => $e->getMessage(),
                'duration_ms' => (int) round((microtime(true) - $started) * 1000),
                'completed_at' => now(),
            ]);
        }
    }
}
