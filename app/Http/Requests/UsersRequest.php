<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'=>'required',
            'password'=>'required',
            'email'=>'required|email',
        ];
    }

    public function messages()
    {
        return[
            'name.required'=>'El campo nombre es requerido',
            'password.required'=>'El campo password es requerido',
            'email.required'=>'El campo e-mail es requerido',
            'email.email'=>'Campo de tipo e-mail',
        ];
    }

}