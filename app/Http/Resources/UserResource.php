<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

class UserResource extends JsonResource
{
    #[OA\Schema(
        schema: 'UserResource',
        properties: [
            new OA\Property(property: 'id', type: 'integer'),
            new OA\Property(property: 'name', type: 'string'),
            new OA\Property(property: 'email', type: 'string'),
            new OA\Property(property: 'status', type: 'string'),
            new OA\Property(property: 'roles', type: 'string')

        ],
        type: 'object'
    )]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'roles' => RoleResource::collection($this->whenLoaded('roles'))
        ];
    }
}
