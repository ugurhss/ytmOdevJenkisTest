<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'groups_name' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'groups_name.required' => 'Grup adı alanı zorunludur.',
            'groups_name.string'   => 'Grup adı geçerli bir metin olmalıdır.',
            'groups_name.max'      => 'Grup adı maksimum 255 karakter olabilir.',
        ];
    }
}
