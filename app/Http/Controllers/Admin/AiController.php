<?php

namespace App\Http\Controllers\Admin;

use App\Data\Ai\AiOperationResult;
use App\Jobs\RunAiTaskJob;
use App\Models\AiTask;
use App\Services\AIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiController extends AdminBaseController
{
    public function __construct(
        private AIService $aiService
    ) {
        parent::__construct();
    }

    public function generatePage(Request $request): JsonResponse
    {
        $request->validate([
            'topic' => 'required|string|max:500',
            'tone' => 'nullable|string|max:30',
            'language' => 'nullable|string|max:5',
            'block_types' => 'nullable|array',
        ]);

        $result = $this->aiService->generatePageBlocks(
            $request->input('topic'),
            $request->input('tone', 'professional'),
            $request->input('language', 'nl'),
            $request->input('block_types', ['hero', 'text', 'cta'])
        );

        if (! $result['success']) {
            return $this->failure($result['error'] ?? 'Generation failed.', 422);
        }

        return $this->success([
            'blocks' => $result['blocks'] ?? [],
            'provider' => $result['provider'] ?? null,
            'model' => $result['model'] ?? null,
            'duration_ms' => $result['duration_ms'] ?? null,
        ]);
    }

    public function generateArticle(Request $request): JsonResponse
    {
        $request->validate([
            'topic' => 'required|string|max:500',
            'type' => 'nullable|string|max:20',
            'category' => 'nullable|string|max:100',
            'tone' => 'nullable|string|max:30',
            'language' => 'nullable|string|max:5',
            'length' => 'nullable|integer|min:200|max:5000',
        ]);

        $result = $this->aiService->generateArticle(
            $request->input('topic'),
            $request->input('type', 'article'),
            $request->input('category'),
            $request->input('tone', 'informative'),
            $request->input('language', 'nl'),
            $request->input('length', 1000)
        );

        if (! $result['success']) {
            return $this->failure($result['error'] ?? 'Generation failed.', 422);
        }

        return $this->success([
            'article' => $result['article'] ?? [],
            'provider' => $result['provider'] ?? null,
            'model' => $result['model'] ?? null,
            'duration_ms' => $result['duration_ms'] ?? null,
        ]);
    }

    public function optimizeSeo(Request $request): JsonResponse
    {
        $request->validate([
            'content' => 'required|string|min:50',
        ]);

        $result = $this->aiService->optimizeSEO($request->input('content'));

        if (! $result['success']) {
            return $this->failure($result['error'] ?? 'Optimization failed.', 422);
        }

        return $this->success([
            'seo' => $result['seo'] ?? [],
            'provider' => $result['provider'] ?? null,
            'model' => $result['model'] ?? null,
            'duration_ms' => $result['duration_ms'] ?? null,
        ]);
    }

    public function draftReply(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string',
            'tone' => 'nullable|string|max:30',
            'language' => 'nullable|string|max:5',
            'contact_id' => 'nullable|integer|exists:contacts,id',
        ]);

        $draft = $this->aiService->draftReply(
            $request->input('message'),
            $request->input('tone', 'professional'),
            $request->input('language', 'nl'),
            $request->filled('contact_id') ? (int) $request->input('contact_id') : null
        );

        if (empty($draft)) {
            return $this->failure('Draft generation failed.', 422);
        }

        return $this->success(['draft' => $draft]);
    }

    public function contentPlan(Request $request): JsonResponse
    {
        $request->validate([
            'topic' => 'required|string|max:500',
            'items' => 'nullable|integer|min:1|max:20',
            'language' => 'nullable|string|max:5',
        ]);

        $result = $this->aiService->generateContentPlan(
            $request->input('topic'),
            $request->input('items', 5),
            $request->input('language', 'nl')
        );

        if (! $result['success']) {
            return $this->failure($result['error'] ?? 'Plan generation failed.', 422);
        }

        return $this->success([
            'plan' => $result['plan'] ?? [],
            'provider' => $result['provider'] ?? null,
            'model' => $result['model'] ?? null,
            'duration_ms' => $result['duration_ms'] ?? null,
        ]);
    }

    public function queueTask(Request $request): JsonResponse
    {
        $request->validate([
            'task_type' => 'required|in:generate_page,generate_article,optimize_seo,draft_reply,content_plan',
            'payload' => 'required|array',
        ]);

        $task = AiTask::query()->create([
            'user_id' => Auth::id(),
            'task_type' => (string) $request->input('task_type'),
            'status' => AiTask::STATUS_PENDING,
            'payload' => $request->input('payload'),
        ]);

        RunAiTaskJob::dispatch($task->id)->onQueue('default');

        return $this->success([
            'task_id' => $task->id,
            'status' => $task->status,
        ], 202);
    }

    public function taskStatus(AiTask $task): JsonResponse
    {
        return $this->success([
            'task' => [
                'id' => $task->id,
                'task_type' => $task->task_type,
                'status' => $task->status,
                'provider' => $task->provider,
                'model' => $task->model,
                'duration_ms' => $task->duration_ms,
                'error_message' => $task->error_message,
                'result' => $task->result,
                'started_at' => $task->started_at,
                'completed_at' => $task->completed_at,
                'created_at' => $task->created_at,
            ],
        ]);
    }

    public function metrics(): JsonResponse
    {
        $base = AiTask::query()->where('created_at', '>=', now()->subDay());
        $total = (clone $base)->count();
        $failed = (clone $base)->where('status', AiTask::STATUS_FAILED)->count();
        $completed = (clone $base)->where('status', AiTask::STATUS_COMPLETED)->count();
        $avgDuration = (int) round((clone $base)->whereNotNull('duration_ms')->avg('duration_ms') ?? 0);
        $byProvider = (clone $base)
            ->selectRaw('provider, count(*) as total')
            ->whereNotNull('provider')
            ->groupBy('provider')
            ->pluck('total', 'provider')
            ->toArray();

        return $this->success([
            'window' => '24h',
            'total' => $total,
            'completed' => $completed,
            'failed' => $failed,
            'failure_rate' => $total > 0 ? round(($failed / $total) * 100, 2) : 0,
            'avg_duration_ms' => $avgDuration,
            'by_provider' => $byProvider,
        ]);
    }

    private function success(array $payload, int $status = 200): JsonResponse
    {
        $result = AiOperationResult::success($payload);

        return response()->json($result->toApiArray(), $status);
    }

    private function failure(string $message, int $status = 422): JsonResponse
    {
        $result = AiOperationResult::failure($message);

        return response()->json($result->toApiArray(), $status);
    }
}
