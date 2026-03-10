<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupMemberRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'role'   => 'sometimes|in:admin,moderator,member',
            'status' => 'sometimes|in:active,banned',
        ];
    }
}
