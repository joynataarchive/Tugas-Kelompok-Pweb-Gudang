<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'year'  => ['nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
        ];
    }

    public function messages(): array
    {
        return [
            'month.min' => 'Bulan harus berada di antara 1 hingga 12.',
            'month.max' => 'Bulan harus berada di antara 1 hingga 12.',
            'year.min'  => 'Tahun tidak valid.',
        ];
    }

    /**
     * Ambil bulan dengan fallback ke bulan berjalan.
     */
    public function selectedMonth(): int
    {
        return (int) $this->input('month', now()->month);
    }

    /**
     * Ambil tahun dengan fallback ke tahun berjalan.
     */
    public function selectedYear(): int
    {
        return (int) $this->input('year', now()->year);
    }
}
