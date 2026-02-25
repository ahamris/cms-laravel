<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\BlogTypeRequest;
use App\Models\BlogType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BlogTypeController extends AdminBaseController
{
    public function index(): View
    {
        $blogTypes = BlogType::orderBy('name')->paginate(20);

        return view('admin.blog-type.index', compact('blogTypes'));
    }

    public function create(): View
    {
        return view('admin.blog-type.create');
    }

    public function store(BlogTypeRequest $request): RedirectResponse
    {
        BlogType::create($request->validated());

        return redirect()->route('admin.blog-type.index')
            ->with('success', 'Blog type created successfully.');
    }

    public function show(BlogType $blogType): View
    {
        return view('admin.blog-type.show', compact('blogType'));
    }

    public function edit(BlogType $blogType): View
    {
        return view('admin.blog-type.edit', compact('blogType'));
    }

    public function update(BlogTypeRequest $request, BlogType $blogType): RedirectResponse
    {
        $blogType->update($request->validated());

        return redirect()->route('admin.blog-type.index')
            ->with('success', 'Blog type updated successfully.');
    }

    public function destroy(BlogType $blogType): RedirectResponse
    {
        $blogType->blogs()->update(['blog_type_id' => null]);
        $blogType->delete();

        return redirect()->route('admin.blog-type.index')
            ->with('success', 'Blog type deleted successfully.');
    }
}
