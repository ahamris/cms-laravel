<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\AcademyVideoRequest;
use App\Models\AcademyCategory;
use App\Models\AcademyChapter;
use App\Models\AcademyVideo;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AcademyVideoController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.content.academy-video.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = AcademyCategory::active()->ordered()->get();
        $initialCategoryId = old('academy_category_id', $categories->first()?->id);
        $chapterOptions = ['' => 'Select chapter'];
        if ($initialCategoryId) {
            $chapterOptions += AcademyChapter::where('academy_category_id', $initialCategoryId)->ordered()->get()->mapWithKeys(fn ($c) => [$c->id => $c->name])->all();
        }
        return view('admin.content.academy-video.create', compact('categories', 'chapterOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AcademyVideoRequest $request)
    {
        $data = $request->safe()->except(['video', 'thumbnail']);
        $data['is_active'] = $request->input('is_active', '0') === '1';
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['duration_seconds'] = isset($data['duration_seconds']) ? (int) $data['duration_seconds'] : null;
        $data['academy_chapter_id'] = $request->filled('academy_chapter_id') ? $request->input('academy_chapter_id') : null;

        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('academy-videos', 'public');
            $data['video_url'] = null;
        } else {
            $data['video_path'] = null;
            $data['video_url'] = $request->filled('video_url') ? $request->input('video_url') : null;
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')->store('academy-thumbnails', 'public');
        }

        AcademyVideo::create($data);

        return redirect()->route('admin.content.academy-video.index')
            ->with('success', 'Academy video created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademyVideo $academyVideo): View
    {
        $academyVideo->load('category');
        return view('admin.content.academy-video.show', compact('academyVideo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademyVideo $academyVideo): View
    {
        $categories = AcademyCategory::active()->ordered()->get();
        $chapterOptions = ['' => 'Select chapter'] + AcademyChapter::where('academy_category_id', $academyVideo->academy_category_id)->ordered()->get()->mapWithKeys(fn ($c) => [$c->id => $c->name])->all();
        return view('admin.content.academy-video.edit', compact('academyVideo', 'categories', 'chapterOptions'));
    }

    /**
     * Update the resource in storage.
     */
    public function update(AcademyVideoRequest $request, AcademyVideo $academyVideo)
    {
        $data = $request->safe()->except(['video', 'thumbnail']);
        $data['is_active'] = $request->input('is_active', '0') === '1';
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['duration_seconds'] = isset($data['duration_seconds']) ? (int) $data['duration_seconds'] : null;
        $data['academy_chapter_id'] = $request->filled('academy_chapter_id') ? $request->input('academy_chapter_id') : null;

        if ($request->has('remove_video') && $request->input('remove_video') === '1') {
            if ($academyVideo->video_path) {
                Storage::disk('public')->delete($academyVideo->video_path);
            }
            $data['video_path'] = null;
            $data['video_url'] = null;
        } elseif ($request->hasFile('video')) {
            if ($academyVideo->video_path) {
                Storage::disk('public')->delete($academyVideo->video_path);
            }
            $data['video_path'] = $request->file('video')->store('academy-videos', 'public');
            $data['video_url'] = null;
        } else {
            if ($academyVideo->video_path) {
                Storage::disk('public')->delete($academyVideo->video_path);
            }
            $data['video_path'] = null;
            $data['video_url'] = $request->filled('video_url') ? $request->input('video_url') : null;
        }

        if ($request->has('remove_thumbnail') && $request->input('remove_thumbnail') === '1') {
            if ($academyVideo->thumbnail_path) {
                Storage::disk('public')->delete($academyVideo->thumbnail_path);
            }
            $data['thumbnail_path'] = null;
        } elseif ($request->hasFile('thumbnail')) {
            if ($academyVideo->thumbnail_path) {
                Storage::disk('public')->delete($academyVideo->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')->store('academy-thumbnails', 'public');
        }

        $academyVideo->update($data);

        return redirect()->route('admin.content.academy-video.index')
            ->with('success', 'Academy video updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademyVideo $academyVideo)
    {
        if ($academyVideo->video_path) {
            Storage::disk('public')->delete($academyVideo->video_path);
        }
        if ($academyVideo->thumbnail_path) {
            Storage::disk('public')->delete($academyVideo->thumbnail_path);
        }
        $academyVideo->delete();

        return redirect()->route('admin.content.academy-video.index')
            ->with('success', 'Academy video deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(AcademyVideo $academyVideo)
    {
        $academyVideo->update(['is_active' => ! $academyVideo->is_active]);
        return response()->json([
            'success' => true,
            'is_active' => $academyVideo->is_active,
            'message' => $academyVideo->is_active ? 'Video activated.' : 'Video deactivated.',
        ]);
    }
}
