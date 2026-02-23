<?php

namespace App\View\Composers;

use App\Models\CourseCategory;
use Illuminate\View\View;

class CourseSidebarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $courseCategories = CourseCategory::active()
            ->ordered()
            ->withCount(['videos' => fn ($q) => $q->active()])
            ->withCount(['videos as documentation_count' => fn ($q) => $q->active()->plainDocumentation()])
            ->withSum(['videos' => fn ($q) => $q->active()], 'duration_seconds')
            ->get();

        $view->with('courseCategories', $courseCategories);
    }
}
