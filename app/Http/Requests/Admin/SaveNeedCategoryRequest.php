<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveNeedCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('need_categories', 'name')->ignore($this->route('category'))],
            'target' => ['required', Rule::in(['anak', 'lansia', 'umum'])],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
