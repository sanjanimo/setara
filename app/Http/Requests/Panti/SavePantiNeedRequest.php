<?php

namespace App\Http\Requests\Panti;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePantiNeedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'need_category_id' => ['required', 'exists:need_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:50'],
            'quantity_needed' => ['required', 'integer', 'min:0'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'stock_days_remaining' => ['required', 'integer', 'min:0'],
            'priority' => ['required', Rule::in(['rendah', 'sedang', 'tinggi', 'kritis'])],
            'status' => ['required', Rule::in(['aktif', 'terpenuhi', 'ditunda'])],
            'note' => ['nullable', 'string'],
        ];
    }
}
