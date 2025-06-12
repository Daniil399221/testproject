<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

class TaskResource extends JsonResource
{
  #[OA\Schema(
      schema: 'TaskResource',
      properties: [
          new OA\Property(property: 'id', type: 'integer'),
          new OA\Property(property: 'title', type: 'string'),
          new OA\Property(property: 'description', type: 'string'),
          new OA\Property(property: 'status', type: 'string'),
          new OA\Property(property: 'assignees', type: 'array',
              items: new OA\Items(type: 'integer'))
      ],
      type: 'object'
  )]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'assignees' => UserResource::collection($this->assignees)
        ];
    }
}
