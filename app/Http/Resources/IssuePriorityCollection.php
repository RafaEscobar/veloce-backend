<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class IssuePriorityCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => IssuePriorityResource::collection($this->collection),
        ];
    }

    public function paginationInformation($request, $paginated, $default): array
    {
        return [];
    }
}
