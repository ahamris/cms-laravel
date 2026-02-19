<?php

namespace App\Http\Controllers\Admin\Administrator;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends AdminBaseController
{
    /**
     * Display a listing of subscriptions.
     */
    public function index(Request $request): View
    {
        return view('admin.administrator.subscriptions.index');
    }

    /**
     * Show the form for creating a new subscription.
     */
    public function create(): View
    {
        return view('admin.administrator.subscriptions.create');
    }

    /**
     * Store a newly created subscription.
     */
    public function store(SubscriptionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $subscription = Subscription::create($validated);

        // Check if user wants to continue editing
        if ($request->input('action') === 'save_and_stay') {
            return redirect()->route('admin.administrator.subscriptions.edit', $subscription)
                ->with('success', 'Subscription created successfully! You can continue editing.');
        }

        return redirect()->route('admin.administrator.subscriptions.index')
            ->with('success', 'Subscription created successfully!');
    }

    /**
     * Display the specified subscription.
     */
    public function show(Subscription $subscription): View
    {
        return view('admin.administrator.subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified subscription.
     */
    public function edit(Subscription $subscription): View
    {
        return view('admin.administrator.subscriptions.edit', compact('subscription'));
    }

    /**
     * Update the specified subscription.
     */
    public function update(SubscriptionRequest $request, Subscription $subscription): RedirectResponse
    {
        $validated = $request->validated();

        $subscription->update($validated);

        // Check if user wants to continue editing
        if ($request->input('action') === 'save_and_stay') {
            return redirect()->route('admin.administrator.subscriptions.edit', $subscription)
                ->with('success', 'Subscription updated successfully! You can continue editing.');
        }

        return redirect()->route('admin.administrator.subscriptions.index')
            ->with('success', 'Subscription updated successfully!');
    }

    /**
     * Remove the specified subscription.
     */
    public function destroy(Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()->route('admin.administrator.subscriptions.index')
            ->with('success', 'Subscription deleted successfully!');
    }

    /**
     * Update subscription status
     */
    public function updateStatus(Request $request, Subscription $subscription): JsonResponse
    {
        $request->validate(['status' => 'required|in:new,contacted,demo_scheduled,demo_completed,converted,rejected']);

        $subscription->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    /**
     * Add admin notes to subscription
     */
    public function addNotes(Request $request, Subscription $subscription): JsonResponse|RedirectResponse
    {
        $request->validate(['admin_notes' => 'required|string']);

        $subscription->update(['admin_notes' => $request->admin_notes]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Notes added successfully.']);
        }

        return back()->with('success', 'Notes added successfully.');
    }

    /**
     * Toggle active status of subscription
     */
    public function toggleActive(Request $request, Subscription $subscription): JsonResponse
    {
        $request->validate(['is_active' => 'sometimes|boolean']);

        $isActive = $request->has('is_active') ? $request->boolean('is_active') : !$subscription->is_active;
        
        $subscription->update(['is_active' => $isActive]);

        return response()->json([
            'success' => true,
            'is_active' => $subscription->is_active,
            'message' => $subscription->is_active ? 'Subscription activated.' : 'Subscription deactivated.'
        ]);
    }

    /**
     * Export subscriptions to CSV
     */
    public function export(Request $request)
    {
        $query = Subscription::query();

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('active')) {
            if ($request->active === '1') {
                $query->active();
            } else {
                $query->where('is_active', false);
            }
        }

        $subscriptions = $query->orderBy('created_at', 'desc')->get();

        $filename = 'subscriptions_'.now()->format('Y_m_d_H_i_s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($subscriptions) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID', 'Full Name', 'Email', 'Phone', 'Company', 'Job Title',
                'Product Interest', 'Status', 'Created At', 'Contacted At',
                'Demo Scheduled', 'Demo Completed', 'Source', 'Active',
            ]);

            // CSV Data
            foreach ($subscriptions as $app) {
                fputcsv($file, [
                    $app->id,
                    $app->full_name,
                    $app->email,
                    $app->phone,
                    $app->company_name,
                    $app->job_title,
                    $app->product_interest,
                    $app->formatted_status,
                    $app->created_at?->format('Y-m-d H:i:s'),
                    $app->contacted_at?->format('Y-m-d H:i:s'),
                    $app->demo_scheduled_at?->format('Y-m-d H:i:s'),
                    $app->demo_completed_at?->format('Y-m-d H:i:s'),
                    $app->source,
                    $app->is_active ? 'Yes' : 'No',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
