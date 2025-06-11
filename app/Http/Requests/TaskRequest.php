<?php

namespace App\Http\Requests;

use App\Enums\UserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'status' => ['sometimes', Rule::in(UserStatus::cases())],
            'assignees' => ['sometimes', 'array'],
            'assignees.*' => ['exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('Поле название обязательное'),
            'description.required' => __('Поле описание обязательное'),
            'status.required' => __('Поле статус обязательное'),
        ];
    }
}
