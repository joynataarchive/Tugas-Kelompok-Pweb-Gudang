<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:100'],
            'plate_number' => ['required', 'string', 'max:20', Rule::unique('vehicles', 'plate_number')->ignore($this->route('vehicle'))],
            'type'         => ['required', 'string', 'max:50'],
            'status'       => ['required', Rule::in(['available', 'borrowed', 'maintenance'])],
            'notes'        => ['nullable', 'string', 'max:500'],
        ];
    }
}
