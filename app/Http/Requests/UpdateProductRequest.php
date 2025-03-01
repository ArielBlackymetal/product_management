<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'description' => 'sometimes|required|string',
            'category_id' => 'sometimes|required',
            'image' => 'sometimes|nullable|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un valor numérico.',
            'description.required' => 'La descripción es obligatoria.',
            'category_id.required' => 'La categoría es obligatoria.',
            'image.image' => 'El archivo debe ser una imagen.',
        ];
    }
}
