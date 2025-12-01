<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'group_id' => 'required|exists:groups,id',
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'group_id.required' => 'Grup seçimi zorunludur.',
            'group_id.exists'   => 'Seçilen grup bulunamadı.',
            'title.required'    => 'Başlık zorunludur.',
            'title.max'         => 'Başlık en fazla 255 karakter olabilir.',
            'content.required'  => 'İçerik zorunludur.',
        ];
    }
}
