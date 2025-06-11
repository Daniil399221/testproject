<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Get(
        path: '/api/users',
        operationId: 'users.index',
        description: 'Возвращает пагинированный список пользователей',
        summary: 'Получить список пользователей',
        tags: ['Пользователи'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список пользователей',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/UserResource')
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
        $users = User::query()->paginate(10);

        return new JsonResponse([
            'data' => UserResource::collection($users),
            'current_page' => $users->currentPage(),
            'per_page' => $users->perPage(),
            'total' => $users->total()
        ]);
    }


    #[OA\Post(
        path: '/api/users/store',
        operationId: 'users.store',
        description: 'Создает пользователя',
        summary: 'Создать пользователя',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UserRequest')
        ),
        tags: ['Пользователи'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Задача создана',
                content: new OA\JsonContent(ref: '#/components/schemas/UserResource')
            )
        ]
    )]
    public function store(UserRequest $request)
    {
         $data = $request->validated();
         $user = User::create($data);

         return response(UserResource::make($user), 201);
    }

    #[OA\Get(
        path: '/api/users/show/{user}',
        operationId: 'users.show',
        description: 'Возвращает конкретного пользователя',
        summary: 'Получить пользователя',
        tags: ['Пользователи'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Пользователь',
                content: new OA\JsonContent(ref: '#/components/schemas/UserResource')
            )
        ]
    )]
    public function show(User $user)
    {
        return response(UserResource::make($user), 200);
    }

    #[OA\Put(
        path: '/api/users/update/{user}',
        operationId: 'users.update',
        description: 'Обновляет пользователя',
        summary: 'Обновить пользователя',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UserRequest')
        ),
        tags: ['Пользователи'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Пользователь обновлен',
                content: new OA\JsonContent(ref: '#/components/schemas/UserResource')
            )
        ]
    )]
    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);

        return response(UserResource::make($user), 200);
    }

    #[OA\Delete(
        path: '/api/users/destroy/{user}',
        operationId: 'users.destroy',
        description: 'Удаляет пользователя',
        summary: 'Удалить пользователя',
        tags: ['Пользователи'],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Пользователь удален'
            )
        ]
    )]
    public function destroy(User $user)
    {
        $user->delete();

        return response(null, 204);
    }
}
