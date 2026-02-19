<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Changelog;
use Illuminate\Http\Request;

class ApiChangelogController extends AdminBaseController
{
    /**
     * Display a listing of API changelog entries.
     */
    public function index()
    {
        $changelogs = Changelog::with([])
            ->where('status', 'api')
            ->orderBy('sort_order', 'asc')
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('admin.content.api-changelog.index', compact('changelogs'));
    }

    /**
     * Display the specified API changelog entry.
     */
    public function show(Changelog $changelog)
    {
        // Ensure this is an API changelog
        if ($changelog->status !== 'api') {
            abort(404, 'API changelog not found');
        }

        return view('admin.content.api-changelog.show', compact('changelog'));
    }
}
