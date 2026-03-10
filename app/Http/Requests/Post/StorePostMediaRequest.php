<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostMediaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'media_url'  => 'required|url|max:500',
            'media_type' => 'required|in:image,video',
            'sort_order' => 'sometimes|integer|min:0',
        ];
    }
}
