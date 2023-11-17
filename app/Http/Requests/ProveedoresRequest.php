<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProveedoresRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'nit'=>'required',
            'nombre'=>'required',
            'direccion'=>'required',
            'telefono'=>'required',
            'email'=>'required|email',
        ];
    }

    public function messages()
    {
        return[
            'nit.required'=>'El campo nit es requerido',
            'nombre.required'=>'El campo nombre es requerido',
            'direccion.required'=>'El campo dirección es requerido',
            'telefono.required'=>'El campo teléfono es requerido',
            'email.required'=>'El campo e-mail es requerido',
            'email.email'=>'Campo de tipo e-mail',
        ];
    }
}
