<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index()
    {
        $pemasoks = Pemasok::latest()->paginate(10);
        return view('pemasok.index', compact('pemasoks'));
    }

    public function create()
    {
        return view('pemasok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_distributor' => 'required|string|max:150',
            'no_telepon'       => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:100',
            'alamat'           => 'nullable|string|max:255',
        ]);

        Pemasok::create([
            'nama_distributor' => $request->nama_distributor,
            'no_telepon'       => $request->no_telepon,
            'email'            => $request->email,
            'alamat'           => $request->alamat,
        ]);

        return redirect()->route('pemasok.index')
            ->with('success', 'Pemasok berhasil ditambahkan.');
    }

    public function edit(Pemasok $pemasok)
    {
        return view('pemasok.edit', compact('pemasok'));
    }

    public function update(Request $request, Pemasok $pemasok)
    {
        $request->validate([
            'nama_distributor' => 'required|string|max:255',
            'no_telepon'       => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:255',
            'alamat'           => 'nullable|string',
        ]);

        $pemasok->update([
            'nama_distributor' => $request->nama_distributor,
            'no_telepon'       => $request->no_telepon,
            'email'            => $request->email,
            'alamat'           => $request->alamat,
        ]);

        return redirect()
            ->route('pemasok.index')
            ->with('success', 'Data pemasok berhasil diperbarui.');
    }

    public function destroy(Pemasok $pemasok)
    {
        if ($pemasok->pembelians()->exists()) {
            return redirect()->route('pemasok.index')
                ->with('error', 'Pemasok tidak bisa dihapus karena masih memiliki data pembelian.');
        }

        $pemasok->delete();

        return redirect()->route('pemasok.index')
            ->with('success', 'Pemasok berhasil dihapus.');
    }
}
