<?php

namespace App\Models;

/*
|--------------------------------------------------------------------------
| Importaciones
|--------------------------------------------------------------------------
|
| Aquí se cargan las clases necesarias para que este modelo funcione.
|
*/

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/*
|--------------------------------------------------------------------------
| Modelo GasolineRefill
|--------------------------------------------------------------------------
|
| Este modelo representa la tabla "gasoline_refills".
|
| Cada registro representa una recarga de gasolina realizada
| a un vehículo.
|
| Ejemplo de un registro:
|
| - vehículo: auto principal
| - monto: 500.00
| - litros: 24.80
| - fecha: 2026-05-05
| - gasolinera: Pemex Tollocan
|
| En Laravel, cada fila de esa tabla se convierte en un objeto
| GasolineRefill.
|
*/

class GasolineRefill extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Trait HasFactory
    |--------------------------------------------------------------------------
    |
    | Permite generar datos falsos automáticamente mediante factories.
    |
    | Ejemplo:
    | GasolineRefill::factory()->create();
    |
    */
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | $fillable
    |--------------------------------------------------------------------------
    |
    | Define qué campos pueden asignarse de forma masiva.
    |
    | Esto permite crear registros así:
    |
    | GasolineRefill::create([
    |     'vehicle_id' => 1,
    |     'amount' => 500,
    |     'liters' => 24.8,
    |     'date' => '2026-05-05',
    |     'gas_station' => 'Pemex'
    | ]);
    |
    */
    protected $fillable = [
        /*
        | Vehículo al que pertenece la recarga
        */
        'vehicle_id',

        /*
        | Monto pagado
        */
        'amount',

        /*
        | Litros cargados
        */
        'liters',

        /*
        | Fecha de la recarga
        */
        'date',

        /*
        | Nombre de la gasolinera
        */
        'gas_station',
    ];

    /*
    |--------------------------------------------------------------------------
    | casts()
    |--------------------------------------------------------------------------
    |
    | Los casts convierten automáticamente ciertos atributos
    | a tipos específicos.
    |
    */
    protected function casts(): array
    {
        return [
            'vehicle_id' => 'integer',
            /*
            |--------------------------------------------------------------------------
            | amount => decimal:2
            |--------------------------------------------------------------------------
            |
            | Siempre devuelve el monto con 2 decimales.
            |
            | Ejemplo:
            | 500 se convertirá en 500.00
            |
            */
            'amount' => 'decimal:2',

            /*
            |--------------------------------------------------------------------------
            | liters => decimal:2
            |--------------------------------------------------------------------------
            |
            | También devuelve los litros con 2 decimales.
            |
            | Ejemplo:
            | 24.8 se convertirá en 24.80
            |
            */
            'liters' => 'decimal:2',

            /*
            |--------------------------------------------------------------------------
            | date => date
            |--------------------------------------------------------------------------
            |
            | Convierte automáticamente el campo en un objeto de fecha.
            |
            | Esto permite hacer cosas como:
            |
            | $refill->date->format('d/m/Y');
            |
            */
            'date' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relación vehicle()
    |--------------------------------------------------------------------------
    |
    | Una recarga de gasolina pertenece a un vehículo.
    |
    | En base de datos esto significa que la tabla
    | gasoline_refills tiene la columna "vehicle_id".
    |
    */
    public function vehicle(): BelongsTo
    {
        /*
        | belongsTo() significa que este modelo depende
        | de otro modelo.
        */
        return $this->belongsTo(Vehicle::class);
    }
}