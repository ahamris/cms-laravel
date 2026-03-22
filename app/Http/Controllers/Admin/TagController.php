<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends AdminBaseController
{
    public function index(): View
    {
        return view('admin.articles.tags.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:tags,slug',
            'type' => 'nullable|string|max:30',
        ]);

        Tag::create($validated);

        $this->logActivity('tag', 'created', "Created tag: {$validated['name']}");

        return redirect()->route('admin.tag.index')
            ->with('success', 'Tag created successfully.');
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:tags,slug,' . $tag->id,
            'type' => 'nullable|string|max:30',
        ]);

        $tag->update($validated);

        $this->logActivity('tag', 'updated', "Updated tag: {$tag->name}");

        return redirect()->route('admin.tag.index')
            ->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        $name = $tag->name;
        $tag->delete();

        $this->logActivity('tag', 'deleted', "Deleted tag: {$name}");

        return redirect()->route('admin.tag.index')
            ->with('success', 'Tag deleted successfully.');
    }

    public function merge(Request $request)
    {
        $validated = $request->validate([
            'source_id' => 'required|exists:tags,id',
            'target_id' => 'required|exists:tags,id|different:source_id',
        ]);

        $source = Tag::findOrFail($validated['source_id']);
        $target = Tag::findOrFail($validated['target_id']);

        \DB::table('taggables')
            ->where('tag_id', $source->id)
            ->update(['tag_id' => $target->id]);

        // Remove duplicates
        \DB::table('taggables')
            ->select('tag_id', 'taggable_id', 'taggable_type')
            ->groupBy('tag_id', 'taggable_id', 'taggable_type')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->each(function ($row) {
                \DB::table('taggables')
                    ->where('tag_id', $row->tag_id)
                    ->where('taggable_id', $row->taggable_id)
                    ->where('taggable_type', $row->taggable_type)
                    ->limit(1)
                    ->delete();
            });

        $source->delete();

        return redirect()->route('admin.tag.index')
            ->with('success', "Tag merged into {$target->name}.");
    }
}
