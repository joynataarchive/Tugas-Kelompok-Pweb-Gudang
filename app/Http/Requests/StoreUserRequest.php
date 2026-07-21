<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:150'],
            'email'       => ['required', 'email', 'max:150', Rule::unique('users', 'email')],
            'password'    => ['required', Password::min(8)],
            'role'        => ['required', 'string', Rule::exists('roles', 'name')],
            'supplier_id' => ['nullable', 'integer', Rule::exists('suppliers', 'id')],
        ];
    }
}
