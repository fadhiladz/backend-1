<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'content'    => 'required_without:media_url|nullable|string|max:5000',
            'media_url'  => 'sometimes|nullable|url|max:500',
            'media_type' => 'required_with:media_url|nullable|in:image,video,file',
        ];
    }
}
