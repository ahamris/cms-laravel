<?php

namespace App\Http\Controllers\Admin\Administrator;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailLogController extends AdminBaseController
{
    /**
     * Display a listing of email logs.
     */
    public function index(Request $request): View
    {
        $query = EmailLog::query()->with('related')->latest();
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by email
        if ($request->filled('email')) {
            $query->where('to_email', 'like', '%' . $request->email . '%');
        }
        
        // Filter by mail class
        if ($request->filled('mail_class')) {
            $query->where('mail_class', $request->mail_class);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $emailLogs = $query->paginate(20);
        
        // Get unique mail classes for filter dropdown
        $mailClasses = EmailLog::distinct()
            ->pluck('mail_class')
            ->filter()
            ->mapWithKeys(fn($mailClass) => [$mailClass => class_basename($mailClass)])
            ->prepend('All Types', '')
            ->toArray();
        
        // Get statistics
        $stats = [
            'total' => EmailLog::count(),
            'sent' => EmailLog::where('status', 'sent')->count(),
            'failed' => EmailLog::where('status', 'failed')->count(),
            'pending' => EmailLog::where('status', 'pending')->count(),
            'today' => EmailLog::whereDate('created_at', today())->count(),
        ];
        
        return view('admin.administrator.email-log.index', compact('emailLogs', 'mailClasses', 'stats'));
    }

    /**
     * Display the specified email log.
     */
    public function show(EmailLog $emailLog): View
    {
        $emailLog->load('related');
        
        return view('admin.administrator.email-log.show', compact('emailLog'));
    }

    /**
     * Remove the specified email log from storage.
     */
    public function destroy(EmailLog $emailLog)
    {
        $emailLog->delete();
        
        return redirect()->route('admin.administrator.email-logs.index')
            ->with('success', 'Email log deleted successfully.');
    }

    /**
     * Bulk delete email logs.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:email_logs,id',
        ]);
        
        EmailLog::whereIn('id', $request->ids)->delete();
        
        return redirect()->route('admin.administrator.email-logs.index')
            ->with('success', count($request->ids) . ' email logs deleted successfully.');
    }
}
