<?php

namespace App\Http\Requests\app\ngo;

use Illuminate\Foundation\Http\FormRequest;

class NgoRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'abbr' => 'required|string|max:50|unique:ngos,abbr',
            'registration_no' => 'required|string|max:100|unique:ngos,registration_no',
            'date_of_establishment' => 'required|date',
            'ngo_type_id' => 'required|integer|exists:ngo_types,id',
            'country_id' => 'required|integer|exists:countries,id',
            'name_en' => 'required|string|unique:ngotrans,name',

        ];
    }
}
