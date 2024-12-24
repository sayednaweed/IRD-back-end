<?php

namespace App\Http\Requests\app;

use Illuminate\Foundation\Http\FormRequest;

class NgoRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:emails,value',
            'district_id' => 'required|integer|exists:districts,id',
            'area' => 'required|string|max:255',
            'abbr' => 'required|string|max:50',
            'registration_no' => 'required|string|max:100|unique:ngos,registration_no',
            'date_of_establishment' => 'required|date',
            'ngo_type_id' => 'required|integer|exists:ngo_types,id',
            'country_id' => 'required|integer|exists:countries,id',
            'name_en' => 'required|string|max:255',
            'vision_en' => 'nullable|string|max:2000',
            'mission_en' => 'nullable|string|max:2000',
            'general_objective_en' => 'nullable|string|max:2000',
            'profile_en' => 'nullable|string|max:2000',
            'objective_en' => 'nullable|string|max:2000',
            'introduction_en' => 'nullable|string|max:2000',
            'name_ps' => 'nullable|string|max:255',
            'vision_ps' => 'nullable|string|max:2000',
            'mission_ps' => 'nullable|string|max:2000',
            'general_objective_ps' => 'nullable|string|max:2000',
            'profile_ps' => 'nullable|string|max:2000',
            'objective_ps' => 'nullable|string|max:2000',
            'introduction_ps' => 'nullable|string|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('validation.required', ['attribute' => 'email']),
            'email.unique' => __('validation.unique', ['attribute' => 'email']),
            // Add custom messages for other fields if needed.
        ];
    }


}
