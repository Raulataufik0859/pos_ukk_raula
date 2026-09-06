<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('search');

        $users = User::with('role')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%')
                      ->orWhere('email', 'like', '%' . $keyword . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id'  => 'required|exists:roles,id',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
        ]);

        return redirect()
            ->route('admin.users')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        // Backend guard: admin tidak bisa diedit (termasuk lewat URL langsung)
        if (strtolower(optional($user->role)->name ?? '') === 'admin') {
            return redirect()
                ->route('admin.users')
                ->with('error', 'Akun admin tidak dapat diedit melalui sistem.');
        }

        $roles = Role::orderBy('name')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Backend guard: tolak update akun admin (proteksi Postman / request langsung)
        if (strtolower(optional($user->role)->name ?? '') === 'admin') {
            return redirect()
                ->route('admin.users')
                ->with('error', 'Akun admin tidak dapat diubah melalui sistem.');
        }

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role_id'  => 'required|exists:roles,id',
        ]);

        // Cegah elevate ke admin secara sembarangan jika perlu — opsional:
        // role_id tetap boleh diisi (kasir -> admin hanya jika admin yang request)

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'role_id' => $request->role_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users')
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        if (strtolower(optional($user->role)->name ?? '') === 'admin') {
            return redirect()
                ->route('admin.users')
                ->with('error', 'Akun admin tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
