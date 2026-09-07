<?php

namespace App\Http\Requests\Produk;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'       => 'required|string|max:150',
            'jenis_id'   => 'required|exists:kategori,id',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok'       => 'required|integer|min:0',
            // Tanpa rule "image" (sering gagal di Windows untuk JPG valid)
            'foto'       => 'nullable|file|mimes:jpeg,jpg,png,webp,gif|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'       => 'Nama produk wajib diisi.',
            'jenis_id.required'   => 'Kategori wajib dipilih.',
            'jenis_id.exists'     => 'Kategori tidak valid.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'stok.required'       => 'Stok wajib diisi.',
            'foto.mimes'          => 'Format foto harus JPG, JPEG, PNG, WEBP, atau GIF.',
            'foto.max'            => 'Ukuran foto maksimal 4MB.',
            'foto.file'           => 'File yang diupload tidak valid.',
        ];
    }
}
