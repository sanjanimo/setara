<?php

namespace App\Http\Requests\Relawan;

use App\Models\YouthProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVolunteerApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'activity_type' => ['required', Rule::in(['kunjungan', 'mentor', 'bantuan_logistik'])],
            'youth_profile_id' => ['nullable', 'integer', 'exists:youth_profiles,id'],
            'motivation' => ['required', 'string', 'max:1000'],
            'skills' => ['nullable', 'string', 'max:500'],
            'organization' => ['nullable', 'string', 'max:255'],
            'availability' => ['nullable', 'string', 'max:255'],
            'scheduled_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $panti = $this->route('panti');

            if ($this->filled('youth_profile_id') && $panti) {
                $youth = YouthProfile::where('id', $this->input('youth_profile_id'))
                    ->where('panti_id', $panti->id)
                    ->first();

                if (! $youth) {
                    $validator->errors()->add(
                        'youth_profile_id',
                        'Youth yang dipilih tidak valid untuk panti ini.'
                    );
                } elseif ($this->input('activity_type') !== 'mentor') {
                    $validator->errors()->add(
                        'youth_profile_id',
                        'Youth hanya dapat dipilih untuk kegiatan mentor.'
                    );
                } elseif (! $youth->mentor_needed || $youth->status !== 'baru') {
                    $validator->errors()->add(
                        'youth_profile_id',
                        'Youth yang dipilih belum tersedia untuk pendampingan.'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'activity_type.required' => 'Pilih jenis kegiatan terlebih dahulu.',
            'motivation.required' => 'Ceritakan motivasi kamu secara singkat.',
            'motivation.max' => 'Motivasi maksimal 1000 karakter.',
            'scheduled_date.after_or_equal' => 'Tanggal kegiatan tidak boleh di masa lalu.',
        ];
    }
}
