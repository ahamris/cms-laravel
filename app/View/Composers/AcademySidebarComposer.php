<?php

namespace App\View\Composers;

use App\Models\AcademyCategory;
use Illuminate\View\View;

class AcademySidebarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $academyCategories = AcademyCategory::active()
            ->ordered()
            ->withCount(['videos' => fn($q) => $q->active()])
            ->withCount(['videos as documentation_count' => fn($q) => $q->active()->plainDocumentation()])
            ->withSum(['videos' => fn($q) => $q->active()], 'duration_seconds')
            ->get();

        $view->with('academyCategories', $academyCategories);
    }
}
