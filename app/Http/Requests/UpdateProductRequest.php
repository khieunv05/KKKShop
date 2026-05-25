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
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'warranty' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean',
            'is_builder' => 'sometimes|boolean',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',
        ];
    }
}
