<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SelfRequest extends Request
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
            'self_name'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'self_name.required' =>'[方案名称]必须填写',
        ];
    }
}
