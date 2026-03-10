<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'content'       => 'required_without:media|nullable|string|max:5000',
            'postable_type' => 'required|in:user,group',
            'postable_id'   => 'required|integer',
            'visibility'    => 'sometimes|in:public,friends,private',
            'media'         => 'sometimes|array|max:10',
            'media.*.url'   => 'required|url|max:500',
            'media.*.type'  => 'required|in:image,video',
        ];
    }
}
