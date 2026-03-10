<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'content'    => 'sometimes|nullable|string|max:5000',
            'visibility' => 'sometimes|in:public,friends,private',
        ];
    }
}
