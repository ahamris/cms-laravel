<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends AdminBaseController
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        parent::__construct();
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display activity logs
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::query()->orderBy('performed_at', 'desc');

        // Filter by user type
        if ($request->filled('user_type')) {
            $query->byUserType($request->user_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('performed_at', '>=', $request->date_from . ' 00:00:00');
        }

        if ($request->filled('date_to')) {
            $query->where('performed_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $activities = $query->paginate(50);

        // Get statistics
        $stats = $this->activityLogService->getActivityStats(30);

        return view('admin.activity-log.index', compact('activities', 'stats'));
    }

    /**
     * Show detailed activity log
     */
    public function show(ActivityLog $activityLog): View
    {
        return view('admin.activity-log.show', compact('activityLog'));
    }

    /**
     * Clean old activity logs
     */
    public function clean(Request $request)
    {
        $days = $request->input('days', 90);
        
        $deletedCount = $this->activityLogService->cleanOldLogs($days);
        
        $this->logAction("Cleaned {$deletedCount} activity logs older than {$days} days");
        
        return redirect()->route('admin.activity-log.index')
            ->with('success', "Cleaned {$deletedCount} old activity logs.");
    }
}
