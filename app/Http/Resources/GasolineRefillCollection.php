<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GasolineRefillCollection extends ResourceCollection
{
    /**
     * Transforma la colección de recursos en un array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => GasolineRefillResource::collection($this->collection),

            'firstPage'   => 1,
            'lastPage'    => $this->lastPage(),
            'total'       => $this->total(),
            'currentPage' => $this->currentPage(),
            'per_page'    => $this->perPage(),
        ];
    }

    /**
     * Personaliza la información de paginación.
     */
    public function paginationInformation($request, $paginated, $default): array
    {
        // Devolvemos un array vacío para evitar la duplicación de metadatos de paginación
        // si se prefiere el formato personalizado arriba.
        return [];
    }
}
