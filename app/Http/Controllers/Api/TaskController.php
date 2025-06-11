<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TaskController extends Controller
{
    #[OA\Get(
        path: '/api/tasks',
        operationId: 'tasks.index',
        description: 'Возвращает пагинированный список задач',
        summary: 'Получить список задач',
        tags: ['Задачи'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список задач',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/TaskResource')
                        ),
                        new OA\Property(property: 'current_page', type: 'integer'),
                        new OA\Property(property: 'per_page', type: 'integer'),
                        new OA\Property(property: 'total', type: 'integer')
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function index()
    {
        $tasks = Task::query()->paginate(10);

        return new JsonResponse([
            'data' => TaskResource::collection($tasks),
            'current_page' => $tasks->currentPage(),
            'per_page' => $tasks->perPage(),
            'total' => $tasks->total()
        ]);

    }

    #[OA\Post(
        path: '/api/tasks/store',
        operationId: 'tasks.store',
        description: 'Создает задачу',
        summary: 'Создать задачу',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TaskRequest')
        ),
        tags: ['Задачи'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Задача создана',
                content: new OA\JsonContent(ref: '#/components/schemas/TaskResource')
            )
        ]
    )]
    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => $data['status'] ?? 'to_do',
        ]);

        if (isset($data['assignees']) && is_array($data['assignees'])) {
            $task->assignees()->sync($data['assignees']);
        }

        return response(TaskResource::make($task->load('assignees')), 201);
    }

    #[OA\Get(
        path: '/api/tasks/show/{id}',
        operationId: 'tasks.show',
        description: 'Возвращает конкретную задачу',
        summary: 'Получить задачу',
        tags: ['Задачи'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Задача',
                content: new OA\JsonContent(ref: '#/components/schemas/TaskResource')
            )
        ]
    )]
    public function show(Task $task)
    {
        return response(TaskResource::make($task->load('assignees')), 200);
    }

    #[OA\Put(
        path: '/api/tasks/update/{id}',
        operationId: 'tasks.update',
        description: 'Обновляет задачу',
        summary: 'Обновить задачу',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TaskRequest')
        ),
        tags: ['Задачи'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Задача обновлена',
                content: new OA\JsonContent(ref: '#/components/schemas/TaskResource')
            )
        ]
    )]
    public function update(TaskRequest $request, Task $task)
    {

        $data = $request->validated();

        $task->update([
            'title' => $data['title'] ?? $task->title,
            'description' => $data['description'] ?? $task->description,
            'status' => $data['status'] ?? $task->status,
        ]);

        if(isset($data['assignees'])) {
            $task->assignees()->sync($data['assignees']);
        }

        return response(TaskResource::make($task->load('assignees')), 200);
    }

    #[OA\Delete(
        path: '/api/tasks/destroy/{id}',
        operationId: 'tasks.destroy',
        description: 'Удаляет задачу',
        summary: 'Удалить задачу',
        tags: ['Задачи'],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Задача удалена'
            )
        ]
    )]
    public function destroy(Task $task)
    {
        $task->delete();
        return response(null, 204);
    }

    #[OA\Post(
        path: '/api/tasks/{id}/assignees',
        operationId: 'tasks.assign',
        description: 'Назначает пользователя на задачу',
        summary: 'Назначить пользователя на задачу',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer')
                ],
                type: 'object'
            )
        ),
        tags: ['Задачи'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Задача обновлена',
                content: new OA\JsonContent(ref: '#/components/schemas/TaskResource')
            )
        ]
    )]
    public function assign(Request $request, Task $task)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $task->assignees()->syncWithoutDetaching([$request->user_id]);

        return response(TaskResource::make($task->load('assignees')), 200);
    }

    #[OA\Delete(
        path: '/api/tasks/{id}/unassign',
        operationId: 'tasks.unassign',
        description: 'Отменяет назначение пользователя на задачу',
        summary: 'Отменить назначение пользователя на задачу',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer')
                ],
                type: 'object'
            )
        ),
        tags: ['Задачи'],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Задача обновлена'
            )
        ]
    )]
    public function unassign(Request $request, Task $task)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $task->assignees()->detach([$request->user_id]);

        return response(TaskResource::make($task->load('assignees')), 204);
    }
}
