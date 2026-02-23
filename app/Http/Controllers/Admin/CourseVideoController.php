<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\CourseVideoRequest;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseVideo;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CourseVideoController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.content.course-video.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = CourseCategory::active()->ordered()->get();
        $initialCategoryId = old('course_category_id', $categories->first()?->id);
        $chapterOptions = ['' => 'Select chapter'];
        if ($initialCategoryId) {
            $chapterOptions += Course::where('course_category_id', $initialCategoryId)->ordered()->get()->mapWithKeys(fn ($c) => [$c->id => $c->name])->all();
        }
        return view('admin.content.course-video.create', compact('categories', 'chapterOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseVideoRequest $request)
    {
        $data = $request->safe()->except(['video', 'thumbnail']);
        $data['is_active'] = $request->input('is_active', '0') === '1';
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['duration_seconds'] = isset($data['duration_seconds']) ? (int) $data['duration_seconds'] : null;
        $data['course_id'] = $request->filled('course_id') ? $request->input('course_id') : null;

        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('course-videos', 'public');
            $data['video_url'] = null;
        } else {
            $data['video_path'] = null;
            $data['video_url'] = $request->filled('video_url') ? $request->input('video_url') : null;
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        CourseVideo::create($data);

        return redirect()->route('admin.content.course-video.index')
            ->with('success', 'Course video created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseVideo $courseVideo): View
    {
        $courseVideo->load('category');
        return view('admin.content.course-video.show', compact('courseVideo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseVideo $courseVideo): View
    {
        $categories = CourseCategory::active()->ordered()->get();
        $chapterOptions = ['' => 'Select chapter'] + Course::where('course_category_id', $courseVideo->course_category_id)->ordered()->get()->mapWithKeys(fn ($c) => [$c->id => $c->name])->all();
        return view('admin.content.course-video.edit', compact('courseVideo', 'categories', 'chapterOptions'));
    }

    /**
     * Update the resource in storage.
     */
    public function update(CourseVideoRequest $request, CourseVideo $courseVideo)
    {
        $data = $request->safe()->except(['video', 'thumbnail']);
        $data['is_active'] = $request->input('is_active', '0') === '1';
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['duration_seconds'] = isset($data['duration_seconds']) ? (int) $data['duration_seconds'] : null;
        $data['course_id'] = $request->filled('course_id') ? $request->input('course_id') : null;

        if ($request->has('remove_video') && $request->input('remove_video') === '1') {
            if ($this->isLocalStoragePath($courseVideo->video_path)) {
                Storage::disk('public')->delete($courseVideo->video_path);
            }
            $data['video_path'] = null;
            $data['video_url'] = null;
        } elseif ($request->hasFile('video')) {
            if ($this->isLocalStoragePath($courseVideo->video_path)) {
                Storage::disk('public')->delete($courseVideo->video_path);
            }
            $data['video_path'] = $request->file('video')->store('course-videos', 'public');
            $data['video_url'] = null;
        } else {
            if ($this->isLocalStoragePath($courseVideo->video_path)) {
                Storage::disk('public')->delete($courseVideo->video_path);
            }
            $data['video_path'] = null;
            $data['video_url'] = $request->filled('video_url') ? $request->input('video_url') : null;
        }

        if ($request->has('remove_thumbnail') && $request->input('remove_thumbnail') === '1') {
            if ($this->isLocalStoragePath($courseVideo->thumbnail_path)) {
                Storage::disk('public')->delete($courseVideo->thumbnail_path);
            }
            $data['thumbnail_path'] = null;
        } elseif ($request->hasFile('thumbnail')) {
            if ($this->isLocalStoragePath($courseVideo->thumbnail_path)) {
                Storage::disk('public')->delete($courseVideo->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        $courseVideo->update($data);

        return redirect()->route('admin.content.course-video.index')
            ->with('success', 'Course video updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseVideo $courseVideo)
    {
        if ($this->isLocalStoragePath($courseVideo->video_path)) {
            Storage::disk('public')->delete($courseVideo->video_path);
        }
        if ($this->isLocalStoragePath($courseVideo->thumbnail_path)) {
            Storage::disk('public')->delete($courseVideo->thumbnail_path);
        }
        $courseVideo->delete();

        return redirect()->route('admin.content.course-video.index')
            ->with('success', 'Course video deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(CourseVideo $courseVideo)
    {
        $courseVideo->update(['is_active' => ! $courseVideo->is_active]);
        return response()->json([
            'success' => true,
            'is_active' => $courseVideo->is_active,
            'message' => $courseVideo->is_active ? 'Video activated.' : 'Video deactivated.',
        ]);
    }

    private function isLocalStoragePath(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }
        return ! str_starts_with($path, 'http://') && ! str_starts_with($path, 'https://');
    }
}
