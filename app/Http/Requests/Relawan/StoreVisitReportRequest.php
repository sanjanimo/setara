<?php

namespace App\Http\Requests\Relawan;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'follow_up_needed' => $this->boolean('follow_up_needed'),
        ]);
    }

    public function rules(): array
    {
        return [
            'activity_date' => ['required', 'date', 'before_or_equal:today'],
            'summary' => ['required', 'string', 'min:20', 'max:2000'],
            'follow_up_needed' => ['nullable', 'boolean'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'activity_date.required' => 'Isi tanggal kegiatan.',
            'activity_date.before_or_equal' => 'Tanggal kegiatan tidak boleh di masa depan.',
            'summary.required' => 'Ringkasan kegiatan wajib diisi.',
            'summary.min' => 'Ringkasan minimal 20 karakter agar informatif.',
        ];
    }
}
