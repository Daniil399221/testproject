<?php

namespace App\Http\Requests;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserRequest',
    required: ['name', 'email', 'password'],
    properties: [
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'email', type: 'string'),
        new OA\Property(property: 'password', type: 'string'),
        new OA\Property(property: 'status', type: 'string'),
    ],
    type: 'object'
)]
class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user'); ;
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => $userId ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'],
            'status' => ['sometimes', Rule::in(UserStatus::cases())],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('Поле имя обязательное'),
            'email.required' => __('Поле email обязательное'),
            'password.required' => __('Поле пароль обязательное'),
            'status.required' => __('Поле статус обязательное'),
        ];
    }
}
