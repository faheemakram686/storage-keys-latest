<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'city_name' => 'required',
            'country_id' => 'required',
            'status' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'city_name.required' => 'City name is required!',
            'country_id.required' => 'Country id is required!',
            'status.required' => 'Status is required!',

        ];
    }
}
