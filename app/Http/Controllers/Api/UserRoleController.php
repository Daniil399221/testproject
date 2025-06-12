<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserRoleController extends Controller
{

    #[OA\Post(
        path: '/api/users/{user}/roles/assign',
        operationId: 'users.roles.assign',
        description: 'Назначает роль пользователю',
        summary: 'Назначить роль',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'role_id', type: 'integer')
                ],
                type: 'object'
            )
        ),
        tags: ['Роли'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Роль назначена',
                content: new OA\JsonContent(ref: '#/components/schemas/UserResource')
            )
        ]
    )]
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
       ]);

        $role = Role::findOrFail($request->role_id);

        if($user->roles()->where('role_id', $role->id)->exists()) {
            return response()->json([
                'message' => 'У пользователя уже есть данная роль',
            ], 422);
        }

        $user->roles()->attach($role->id);
        $user->load('roles');

        return response()->json([
            'message' => 'Роль успешно назначена',
            'user' => UserResource::make($user)
        ], 200);
    }

    #[OA\Delete(
        path: '/api/users/{user}/roles/revoke',
        operationId: 'users.roles.revoke',
        description: 'Снимает роль у пользователя',
        summary: 'Снять роль',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'role_id', type: 'integer')
                ],
                type: 'object'
            )
        ),
        tags: ['Роли'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Роль снята',
                content: new OA\JsonContent(ref: '#/components/schemas/UserResource')
            )
        ]
    )]

    public function revokeRole(Request $request, User $user)
    {

        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->role_id);

        if($user->roles()->where('role_id', $role->id)->exists()) {
            return response()->json([
                'message' => 'У пользователя нет этой роли',
            ], 422);
        }

        $user->roles()->detach($role->id);
        $user->load('roles');

        return response()->json([
            'message' => 'Роль успешно снята',
            'user' => UserResource::make($user)
        ], 200);
    }


   #[OA\Get(
       path: 'api/roles',
       operationId: 'roles.list',
       description: 'Возвращает список ролей',
       summary: 'Список ролей',
       tags: ['Роли'],
       responses: [
           new OA\Response(
               response: 200,
               description: 'Список ролей',
               content: new OA\JsonContent(ref: '#/components/schemas/RoleResource')
           )
       ]
   )]
    public function rolesList(Role $role)
    {
        $role = Role::all();

        return RoleResource::collection($role);
    }
}
