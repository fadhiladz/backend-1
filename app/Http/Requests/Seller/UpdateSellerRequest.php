<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSellerRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'shop_name'        => 'sometimes|string|max:150',
            'shop_description' => 'sometimes|nullable|string|max:1000',
            'shop_logo_url'    => 'sometimes|nullable|url|max:500',
        ];
    }
}
