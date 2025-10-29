<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $productId = $this->route('id');

        return [
            'brand_id' => 'sometimes|required|exists:brands,id',
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($productId),
            ],
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0|max:999999.99',
            'stock_available' => 'sometimes|required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'The image must not be larger than 2MB.',
        ];
    }
}