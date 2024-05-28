<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SettingController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['permission:manage settings']);
    // }

    public function permission()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('setting.permission', compact('roles', 'permissions'));
    }

    public function permissionStore(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'role' => 'required',
            'permissions' => 'required|array',
        ]);
        $role = Role::findByName($request->role);
        $role->syncPermissions($request->permissions);
        return back()->with('success', 'Permission updated successfully');
    }

    public function users()
    {
        $roles = Role::with(['users' => fn ($q) => $q->select('name', 'email')])->get();
        return view('setting.users', compact('roles'));
    }

    public function rolePermission($id)
    {
        $role = Role::findById($id);
        $permissions = Permission::all();
        return view('setting.role-permission', compact('permissions', 'role'));
    }
}
