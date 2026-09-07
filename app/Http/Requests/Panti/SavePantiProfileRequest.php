<?php

namespace App\Http\Requests\Panti;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePantiProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'consent_agreement' => $this->boolean('consent_agreement'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['anak', 'jompo', 'campuran'])],
            'description' => ['nullable', 'string'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'address' => ['nullable', 'string', 'max:500'],
            'manager_name' => ['nullable', 'string', 'max:255'],
            'manager_phone' => ['nullable', 'string', 'max:30'],
            'capacity' => ['required', 'integer', 'min:0'],
            'total_residents' => ['required', 'integer', 'min:0'],
            'children_count' => ['required', 'integer', 'min:0'],
            'elderly_count' => ['required', 'integer', 'min:0'],
            'staff_count' => ['required', 'integer', 'min:0'],
            'location_precision' => ['required', Rule::in(['exact', 'approximate'])],
            'consent_agreement' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'consent_agreement.required' => 'Pernyataan persetujuan wajib dicentang.',
            'consent_agreement.accepted' => 'Pernyataan persetujuan wajib dicentang.',
        ];
    }
}
