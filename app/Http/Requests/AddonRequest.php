<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddonRequest extends FormRequest
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
            'addon_name' => 'required',
            'addon_price' => 'required',
            'status' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'addon_name.required' => 'Addon name is required!',
            'addon_price.required' => 'Addon price is required!',
            'status.required' => 'Status is required!',

        ];
    }
}
