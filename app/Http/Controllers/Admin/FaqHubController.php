<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;

class FaqHubController extends AdminBaseController
{
    /**
     * Entry point for the merged FAQ admin area: sends users to the first section they can access.
     */
    public function index()
    {
        if (Gate::allows('faq_module_access')) {
            return redirect()->route('admin.faq-module.index');
        }

        if (Gate::allows('page_access')) {
            return redirect()->route('admin.element-faq.index');
        }

        abort(403);
    }
}
