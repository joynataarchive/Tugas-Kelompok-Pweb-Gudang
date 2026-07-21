<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // Role default yang TIDAK BOLEH diedit/dihapus
    private const PROTECTED_ROLES = ['Super Admin', 'Staff Gudang', 'Supplier'];

    public function index()
    {
        $roles = Role::withCount('permissions')->orderBy('name')->paginate(10);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('roles.form', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
        ]);

        $role = Role::create(['name' => $request->name]);
        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', "Role '{$role->name}' berhasil dibuat.");
    }

    public function edit(Role $role)
    {
        $this->guardProtected($role);
        $permissions    = Permission::orderBy('name')->get();
        $assignedPerms  = $role->permissions->pluck('name')->toArray();
        return view('roles.form', compact('role', 'permissions', 'assignedPerms'));
    }

    public function update(Request $request, Role $role)
    {
        $this->guardProtected($role);
        $request->validate([
            'name'        => ['required', 'string', 'max:100', "unique:roles,name,{$role->id}"],
            'permissions' => ['nullable', 'array'],
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', "Role '{$role->name}' berhasil diperbarui.");
    }

    public function destroy(Role $role)
    {
        $this->guardProtected($role);
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }

    private function guardProtected(Role $role): void
    {
        if (in_array($role->name, self::PROTECTED_ROLES)) {
            abort(403, "Role default '{$role->name}' tidak dapat diubah.");
        }
    }
}
