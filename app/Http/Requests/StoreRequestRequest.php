<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isUser();
    }

    public function rules(): array
    {
        return [
            'donation_id' => ['required', 'exists:donations,id'],
            'message'     => ['nullable', 'string', 'max:500'],
        ];
    }
}
