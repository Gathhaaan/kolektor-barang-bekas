<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isDonor();
    }

    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:255'],
            'category_id'    => ['required', 'exists:categories,id'],
            'description'    => ['required', 'string', 'min:20'],
            'condition'      => ['required', 'in:baru,sangat_baik,baik,cukup_baik'],
            'pickup_address' => ['required', 'string', 'max:500'],
            'photos'         => ['nullable', 'array', 'max:5'],
            'photos.*'       => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'          => 'Nama barang wajib diisi.',
            'category_id.required'    => 'Kategori wajib dipilih.',
            'description.required'    => 'Deskripsi wajib diisi.',
            'description.min'         => 'Deskripsi minimal 20 karakter.',
            'condition.required'      => 'Kondisi barang wajib dipilih.',
            'pickup_address.required' => 'Alamat pengambilan wajib diisi.',
            'photos.*.image'          => 'File harus berupa gambar.',
            'photos.*.max'            => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
