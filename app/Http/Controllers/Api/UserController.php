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


    public function store(UserRequest $request)
    {
         $data = $request->validated();
         $user = User::create($data);

         return response(UserResource::make($user), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response(UserResource::make(User::find($id)), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $data = $request->validated();
        $user = User::find($id);
        $user->update($data);

        return response(UserResource::make($user), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();

        return response(null, 204);
    }
}
