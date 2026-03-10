<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductImageRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'image_url'  => 'required|url|max:500',
            'sort_order' => 'sometimes|integer|min:0',
        ];
    }
}
