<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonRequest extends FormRequest
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
            'name'=>'required|max:100',
            'email' =>'required|email',
            'mobile' => 'digits:13|bd_mobile',
        ];
    }

    public function messages()
    {
        return [
            'mobile.bd_mobile'    => 'Invalid Bangladeshi MSISDN, must starts with 880'
        ];
    }
}
