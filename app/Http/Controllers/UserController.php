<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->search, fn($q) => $q
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles     = Role::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('users.form', compact('roles', 'suppliers'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $roleName = $data['role'];
        unset($data['role']);

        // supplier_id hanya relevan untuk role Supplier
        if ($roleName !== 'Supplier') {
            $data['supplier_id'] = null;
        }

        $user = User::create($data);
        $user->syncRoles([$roleName]);

        return redirect()->route('users.index')->with('success', "User {$user->name} berhasil ditambahkan.");
    }

    public function edit(User $user)
    {
        $roles     = Role::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('users.form', compact('user', 'roles', 'suppliers'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $roleName = $data['role'];
        unset($data['role']);

        if ($roleName !== 'Supplier') {
            $data['supplier_id'] = null;
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        $user->syncRoles([$roleName]);

        return redirect()->route('users.index')->with('success', "User {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        // Cegah hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
