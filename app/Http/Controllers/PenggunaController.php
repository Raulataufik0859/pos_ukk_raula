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
            ->orderBy('id', 'asc')
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
                ->back()
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        if (strtolower(optional($user->role)->name ?? '') === 'admin') {
            return redirect()
                ->back()
                ->with('error', 'Akun admin tidak dapat dihapus.');
        }

        // Cegah error FK 1451: user masih punya transaksi
        $jumlahPenjualan = \App\Models\Penjualan::where('user_id', $user->id)->count();
        if ($jumlahPenjualan > 0) {
            return redirect()
                ->back()
                ->with('error', "Pengguna tidak dapat dihapus karena masih memiliki {$jumlahPenjualan} data transaksi penjualan.");
        }

        $jumlahProduk = \App\Models\Produk::where('user_id', $user->id)->count();
        if ($jumlahProduk > 0) {
            return redirect()
                ->back()
                ->with('error', "Pengguna tidak dapat dihapus karena masih memiliki {$jumlahProduk} data produk.");
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('kategori', 'user_id')) {
            $jumlahKategori = \App\Models\Kategori::where('user_id', $user->id)->count();
            if ($jumlahKategori > 0) {
                return redirect()
                    ->back()
                    ->with('error', "Pengguna tidak dapat dihapus karena masih terhubung dengan {$jumlahKategori} kategori.");
            }
        }

        if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        $params = [];
        if (request()->filled('return_query')) {
            parse_str(request()->input('return_query'), $params);
        } elseif ($ref = request()->headers->get('referer')) {
            $q = parse_url($ref, PHP_URL_QUERY);
            if ($q) parse_str($q, $params);
        }
        return redirect()->route('admin.users', $params)->with('success', 'Pengguna berhasil dihapus.');
    }
}
