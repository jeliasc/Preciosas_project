<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductosRequest extends FormRequest
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
            'code'=>'required',
            'nombre'=>'required',
            'precio'=>'required|numeric',
            'categoria_id'=>'required',
            'proveedor_id'=>'required',
        ];
    }

    public function messages()
    {
        return[
            'code.required'=>'El campo código es requerido',
            'nombre.required'=>'El campo nombre es requerido',
            'precio.required'=>'El campo precio es requerido',
            'precio.numeric'=>'El precio es de tipo numérico',
            'categoria_id.required'=>'El campo categoría es requerido',
            'proveedor_id.required'=>'El campo proveedor es requerido',
        ];
    }
}
