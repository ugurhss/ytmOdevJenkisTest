<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Yetkilendirmeyi controller'da yaptığımız için true bırakabiliriz
        return true;
    }

    public function rules(): array
    {
        return [
            'groups_name'     => 'required|string|max:255',
            'city_id'         => 'required|exists:cities,id',
            'university_id'   => 'required|exists:universities,id',
            'faculty_id'      => 'required|exists:faculties,id',
            'department_id'   => 'required|exists:departments,id',
            'class_models_id' => 'required|exists:class_models,id',
        ];
    }

    public function messages(): array
    {
        return [
            'groups_name.required'     => 'Grup adı alanı zorunludur.',
            'groups_name.string'       => 'Grup adı geçerli bir metin olmalıdır.',
            'groups_name.max'          => 'Grup adı maksimum 255 karakter olabilir.',

            'city_id.required'         => 'Şehir seçimi zorunludur.',
            'city_id.exists'           => 'Seçilen şehir geçersiz.',

            'university_id.required'   => 'Üniversite seçimi zorunludur.',
            'university_id.exists'     => 'Seçilen üniversite geçersiz.',

            'faculty_id.required'      => 'Fakülte seçimi zorunludur.',
            'faculty_id.exists'        => 'Seçilen fakülte geçersiz.',

            'department_id.required'   => 'Bölüm seçimi zorunludur.',
            'department_id.exists'     => 'Seçilen bölüm geçersiz.',

            'class_models_id.required' => 'Sınıf modeli seçimi zorunludur.',
            'class_models_id.exists'   => 'Seçilen sınıf modeli geçersiz.',
        ];
    }
}
