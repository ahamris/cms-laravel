<?php

namespace App\Http\Controllers\Admin\Element;

use App\Enums\ElementType;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Element;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class BaseTypedElementController extends AdminBaseController
{
    abstract protected function type(): ElementType;

    abstract protected function heading(): string;

    abstract protected function routeBase(): string;

    protected function typeHelp(): string
    {
        return '';
    }

    public function index(): View
    {
        $elements = Element::byType($this->type())->latest()->paginate(20);

        return view('admin.elements.index', [
            'elements' => $elements,
            'heading' => $this->heading(),
            'routeBase' => $this->routeBase(),
            'typeHelp' => $this->typeHelp(),
        ]);
    }

    public function create(): View
    {
        return view('admin.elements.create', [
            'heading' => $this->heading(),
            'routeBase' => $this->routeBase(),
            'type' => $this->type(),
            'typeHelp' => $this->typeHelp(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);

        Element::create([
            'type' => $this->type(),
            'title' => $validated['title'] ?? null,
            'sub_title' => $validated['sub_title'] ?? null,
            'description' => $validated['description'] ?? null,
            'options' => $validated['options'],
        ]);

        return redirect()->route($this->routeBase().'.index')
            ->with('success', $this->heading().' item created successfully.');
    }

    public function show(int $element): View
    {
        $item = $this->findTypedElement($element);

        return view('admin.elements.show', [
            'element' => $item,
            'heading' => $this->heading(),
            'routeBase' => $this->routeBase(),
            'typeHelp' => $this->typeHelp(),
        ]);
    }

    public function edit(int $element): View
    {
        $item = $this->findTypedElement($element);

        return view('admin.elements.edit', [
            'element' => $item,
            'heading' => $this->heading(),
            'routeBase' => $this->routeBase(),
            'type' => $this->type(),
            'typeHelp' => $this->typeHelp(),
        ]);
    }

    public function update(Request $request, int $element): RedirectResponse
    {
        $item = $this->findTypedElement($element);
        $validated = $this->validatePayload($request);

        $item->update([
            'type' => $this->type(),
            'title' => $validated['title'] ?? null,
            'sub_title' => $validated['sub_title'] ?? null,
            'description' => $validated['description'] ?? null,
            'options' => $validated['options'],
        ]);

        return redirect()->route($this->routeBase().'.index')
            ->with('success', $this->heading().' item updated successfully.');
    }

    public function destroy(int $element): RedirectResponse
    {
        $item = $this->findTypedElement($element);
        $item->delete();

        return redirect()->route($this->routeBase().'.index')
            ->with('success', $this->heading().' item deleted successfully.');
    }

    protected function findTypedElement(int $id): Element
    {
        return Element::query()
            ->whereKey($id)
            ->where('type', $this->type())
            ->firstOrFail();
    }

    protected function validatePayload(Request $request): array
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'options' => 'nullable|json',
        ]);

        $validated['options'] = isset($validated['options']) && $validated['options'] !== ''
            ? json_decode($validated['options'], true)
            : null;

        return $validated;
    }
}
