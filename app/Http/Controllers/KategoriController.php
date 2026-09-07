<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::with(['user', 'produks'])
            ->withCount('produks')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kategori,nama',
        ]);

        Kategori::create([
            'nama'    => $request->nama,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kategori,nama,' . $kategori->id,
        ]);

        $kategori->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->produks()->exists()) {
            return redirect()->route('kategori.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih dipakai produk.');
        }

        $kategori->delete();

        $params = [];
        if (request()->filled('return_query')) {
            parse_str(request()->input('return_query'), $params);
        } elseif ($ref = request()->headers->get('referer')) {
            $q = parse_url($ref, PHP_URL_QUERY);
            if ($q) parse_str($q, $params);
        }
        return redirect()->route('kategori.index', $params)->with('success', 'Kategori berhasil dihapus.');
    }
}
