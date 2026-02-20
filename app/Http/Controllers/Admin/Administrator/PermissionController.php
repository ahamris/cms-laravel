<?php

namespace App\Http\Controllers\Admin\Administrator;

use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends AdminBaseController
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $query = Permission::query()->orderBy('name');

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $permissions = $query->get();

        return view('admin.administrator.permissions.index', compact('permissions', 'search'));
    }
}
