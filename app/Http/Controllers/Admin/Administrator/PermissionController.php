<?php

namespace App\Http\Controllers\Admin\Administrator;

use App\Http\Controllers\Admin\AdminBaseController;
use Spatie\Permission\Models\Permission;

class PermissionController extends AdminBaseController
{
    public function index()
    {
        $permissions = Permission::paginate(25);

        return view('admin.administrator.permissions.index', compact('permissions'));
    }
}
