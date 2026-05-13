<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MaintenanceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => MaintenanceResource::collection($this->collection),
            'firstPage' => 1,
            'lastPage' => $this->lastPage(),
            'total' => $this->total(),
            'currentPage' => $this->currentPage(),
            'per_page' => $this->perPage(),
        ];
    }

    /**
     * Customize the pagination information for the resource.
     */
    public function paginationInformation($request, $paginated, $default): array
    {
        return [];
    }
}
