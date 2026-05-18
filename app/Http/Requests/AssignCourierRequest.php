<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignCourierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'courier_id'  => ['required', 'exists:users,id'],
            'pickup_date' => ['required', 'date', 'after_or_equal:today'],
            'pickup_note' => ['nullable', 'string', 'max:500'],
            'request_id'  => ['required', 'exists:donation_requests,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'courier_id.required'  => 'Pilih kurir terlebih dahulu.',
            'pickup_date.required' => 'Tanggal pengambilan wajib diisi.',
            'pickup_date.after_or_equal' => 'Tanggal pengambilan tidak boleh di masa lalu.',
            'request_id.required'  => 'Permintaan penerima wajib dipilih.',
        ];
    }
}
