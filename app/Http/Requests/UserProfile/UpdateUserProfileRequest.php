<?php

namespace App\Http\Requests\UserProfile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'display_name'      => 'sometimes|string|max:100',
            'bio'               => 'sometimes|nullable|string|max:500',
            'profile_image_url' => 'sometimes|nullable|url|max:500',
            'cover_image_url'   => 'sometimes|nullable|url|max:500',
            'date_of_birth'     => 'sometimes|nullable|date|before:today',
            'gender'            => 'sometimes|nullable|in:male,female,other,prefer_not_to_say',
            'location'          => 'sometimes|nullable|string|max:200',
        ];
    }
}
