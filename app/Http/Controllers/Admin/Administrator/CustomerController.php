<?php

namespace App\Http\Controllers\Admin\Administrator;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends AdminBaseController
{
    /**
     * Display a listing of customers.
     */
    public function index(): View
    {
        $customers = User::latest()
            ->paginate(10);

        return view('admin.administrator.customers.index', compact('customers'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(User $customer): View
    {
        return view('admin.administrator.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, User $customer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($customer->id),
            ],
            'secondary_email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'secondary_email')->ignore($customer->id),
            ],
            'email_verified_at' => 'nullable|date',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'secondary_email' => $validated['secondary_email'] ?? null,
        ];

        // Sadece doluysa email_verified_at ekle
        if (! empty(trim($validated['email_verified_at'] ?? ''))) {
            $updateData['email_verified_at'] = $validated['email_verified_at'];
        } else {
            // Boşsa explicit olarak null yap
            $updateData['email_verified_at'] = null;
        }

        $customer->update($updateData);

        return redirect()->route('admin.administrator.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(User $customer): RedirectResponse
    {
        // Prevent deleting yourself
        if (auth()->id() === $customer->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $customer->delete();

        return redirect()->route('admin.administrator.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function activeAccount(User $customer): RedirectResponse
    {
        $customer->update([
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.administrator.customers.index')
            ->with('success', 'Customer account activated successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $ids = $request->ids;

                $ids = array_diff($ids, [auth()->id()]);

                if (! empty($ids)) {
                    User::whereIn('id', $ids)->delete();
                }
            });

            return redirect()->route('admin.administrator.customers.index')
                ->with('success', 'Selected customers deleted successfully (your account was excluded).');

        } catch (\Exception $e) {
            return redirect()->route('admin.administrator.customers.index')
                ->with('error', 'Error deleting customers: '.$e->getMessage());
        }
    }
}
