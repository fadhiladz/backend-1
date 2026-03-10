<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'           => 'sometimes|string|max:200',
            'description'    => 'sometimes|nullable|string|max:5000',
            'price'          => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'category_id'    => 'sometimes|nullable|exists:product_categories,id',
            'status'         => 'sometimes|in:active,draft,archived',
        ];
    }
}
