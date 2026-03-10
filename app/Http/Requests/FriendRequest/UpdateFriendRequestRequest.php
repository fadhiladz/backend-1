<?php

namespace App\Http\Requests\FriendRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFriendRequestRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'status' => 'required|in:accepted,declined',
        ];
    }
}
