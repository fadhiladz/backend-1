<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'            => 'sometimes|string|max:150',
            'description'     => 'sometimes|nullable|string|max:1000',
            'sport_id'        => 'sometimes|nullable|exists:sports,id',
            'cover_image_url' => 'sometimes|nullable|url|max:500',
            'visibility'      => 'sometimes|in:public,private',
        ];
    }
}
