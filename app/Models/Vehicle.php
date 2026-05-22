<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/*
|--------------------------------------------------------------------------
| Modelo Vehicle
|--------------------------------------------------------------------------
|
| Este modelo representa la tabla "vehicles".
|
| Cada objeto Vehicle corresponde a un registro en esa tabla.
|
| Gracias a Eloquent, este modelo permite:
| - crear vehículos
| - consultar vehículos
| - actualizarlos
| - eliminarlos
|
| También define las relaciones que tiene un vehículo
| con otras tablas.
|
*/

class Vehicle extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Trait HasFactory -> Permite crear factories para generar datos falsos.
    |--------------------------------------------------------------------------
    */
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | $fillable -> Define qué campos pueden asignarse masivamente.
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        /*
        | Usuario dueño del vehículo
        */
        'user_id',

        /*
        | Tipo de vehículo
        */
        'vehicle_type_id',

        /*
        | Estado del vehículo
        */
        'vehicle_status_id',

        /*
        | Nombre personalizado del vehículo
        */
        'name',

        /*
        | Placas
        */
        'plates',

        /*
        | Número de serie / VIN
        */
        'serial_number',

        /*
        | Tipo de gasolina
        */
        'gasoline_type',

        /*
        | Tipo de aceite
        */
        'oil_type',

        /*
        | Nombre comercial o modelo
        */
        'model_name',

        /*
        | Ruta o nombre del archivo de la foto
        */
        'photo',

        /*
        | Año del modelo
        */
        'model_year',
    ];

    /*
    |--------------------------------------------------------------------------
    | casts() -> Permite convertir automáticamente atributos a cierto tipo.
    |--------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | model_year
            |--------------------------------------------------------------------------
            |
            | Convierte automáticamente el año a integer.
            |
            | Si viene como texto desde base de datos,
            | Laravel lo entregará como número entero.
            |
            */
            'model_year' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relación user()
    |--------------------------------------------------------------------------
    |
    | Un vehículo pertenece a un usuario.
    |
    | En base de datos esto significa que la tabla vehicles
    | tiene una columna "user_id".
    |
    */
    public function user(): BelongsTo
    {
        /*
        | belongsTo() indica que este modelo depende de otro.
        */
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Relación vehicleType()
    |--------------------------------------------------------------------------
    |
    | Un vehículo pertenece a un tipo de vehículo.
    |
    | Ejemplos:
    | auto, moto, camioneta, etc.
    |
    */
    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Relación vehicleStatus()
    |--------------------------------------------------------------------------
    |
    | Un vehículo pertenece a un estado.
    |
    | Ejemplos:
    | activo, vendido, en reparación.
    |
    */
    public function vehicleStatus(): BelongsTo
    {
        return $this->belongsTo(VehicleStatus::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Relación gasolineRefills()
    |--------------------------------------------------------------------------
    |
    | Un vehículo puede tener muchos registros de gasolina.
    |
    | Ejemplo:
    | - lunes: $300
    | - viernes: $250
    |
    */
    public function gasolineRefills(): HasMany
    {
        return $this->hasMany(GasolineRefill::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Relación maintenances()
    |--------------------------------------------------------------------------
    |
    | Un vehículo puede tener muchos mantenimientos.
    |
    | Ejemplo:
    | - cambio de aceite
    | - cambio de llantas
    |
    */
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Relación reminders()
    |--------------------------------------------------------------------------
    |
    | Un vehículo puede tener muchos recordatorios.
    |
    | Ejemplo:
    | - verificar seguro
    | - cambio de aceite en 30 días
    |
    */
    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }
}
