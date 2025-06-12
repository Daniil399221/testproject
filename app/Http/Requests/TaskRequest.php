<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TaskRequest',
    required: ['title', 'description', 'status'],
    properties: [
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'description', type: 'string'),
        new OA\Property(property: 'status', type: 'string'),
        new OA\Property(property: 'assignees', type: 'array',
            items: new OA\Items(type: 'integer')),
    ],
)]
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
            'status' => ['sometimes', Rule::in(TaskStatus::cases())],
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
            'status.in' => __('Указан недопустимый статус'),
        ];
    }
}
