<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductReviewRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'rating'      => 'required|integer|min:1|max:5',
            'review_text' => 'sometimes|nullable|string|max:2000',
        ];
    }
}
