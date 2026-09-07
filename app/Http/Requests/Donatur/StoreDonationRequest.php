<?php

namespace App\Http\Requests\Donatur;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $user !== null && $user->isDonatur();
    }

    public function rules(): array
    {
        $rules = [
            'type' => ['required', Rule::in(['barang', 'tenaga'])],
            'message' => ['nullable', 'string', 'max:1000'],
        ];

        if ($this->input('type') === 'barang') {
            $rules['panti_need_id'] = ['required', 'integer', 'exists:panti_needs,id'];
            $rules['quantity'] = ['required', 'integer', 'min:1'];
        } else {
            $rules['panti_need_id'] = ['nullable', 'integer'];
            $rules['quantity'] = ['nullable', 'integer'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'panti_need_id.required' => 'Pilih kebutuhan panti untuk bantuan barang.',
            'panti_need_id.exists' => 'Kebutuhan yang dipilih tidak valid.',
            'quantity.required' => 'Isi jumlah bantuan untuk bantuan barang.',
            'quantity.min' => 'Jumlah bantuan minimal 1.',
        ];
    }
}
