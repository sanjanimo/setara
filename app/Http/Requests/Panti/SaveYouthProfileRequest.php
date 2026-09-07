<?php

namespace App\Http\Requests\Panti;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveYouthProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'mentor_needed' => $this->boolean('mentor_needed'),
        ]);
    }

    public function rules(): array
    {
        return [
            'initials' => ['required', 'string', 'max:5'],
            'age' => ['required', 'integer', 'min:13', 'max:22'],
            'interests' => ['required', 'string', 'max:500'],
            'skill_goals' => ['nullable', 'string', 'max:500'],
            'training_needs' => ['nullable', 'string', 'max:500'],
            'mentor_needed' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['baru', 'didampingi', 'selesai'])],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'initials.max' => 'Gunakan inisial singkat (maksimal 5 karakter), bukan nama lengkap.',
        ];
    }
}
