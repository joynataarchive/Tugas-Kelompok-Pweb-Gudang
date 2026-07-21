<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('supplier');
        return [
            'name'           => ['required', 'string', 'max:150', Rule::unique('suppliers', 'name')->ignore($id)],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'email'          => ['nullable', 'email', 'max:150', Rule::unique('suppliers', 'email')->ignore($id)],
            'address'        => ['nullable', 'string', 'max:500'],
        ];
    }
}
