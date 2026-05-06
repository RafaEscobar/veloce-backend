<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Migración para crear la tabla "vehicles"
|--------------------------------------------------------------------------
|
| Esta migración se encarga de crear la tabla donde se guardará
| la información de los vehículos.
|
| Un vehículo pertenece a:
| - un usuario
| - un tipo de vehículo
| - un estado de vehículo
|
| Además guarda datos básicos como placas, número de serie,
| tipo de gasolina, tipo de aceite, foto y año del modelo.
|
*/

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Método up()
    |--------------------------------------------------------------------------
    |
    | Este método se ejecuta cuando corres:
    | php artisan migrate
    |
    | Aquí se crea la tabla "vehicles" desde cero.
    |
    */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            /*
            |--------------------------------------------------------------------------
            | id()
            |--------------------------------------------------------------------------
            |
            | Crea una columna llamada "id" que será:
            | - entera
            | - autoincremental
            | - llave primaria de la tabla
            |
            | Esto significa que cada vehículo tendrá un identificador único.
            |
            */
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | name
            |--------------------------------------------------------------------------
            |
            | Guarda el nombre del vehículo.
            | Ejemplo:
            | "Mi camioneta", "Auto de viaje", etc.
            |
            | string() crea una columna de texto corto.
            */
            $table->string('name');

            /*
            |--------------------------------------------------------------------------
            | user_id
            |--------------------------------------------------------------------------
            |
            | Esta columna guarda el usuario al que pertenece el vehículo.
            |
            | foreignId('user_id') crea una columna entera pensada para relación.
            | constrained() le dice a Laravel que esta columna apunta a la tabla
            | relacionada automáticamente, en este caso "users".
            |
            | onDelete('cascade') significa:
            | si se elimina el usuario, también se eliminan sus vehículos.
            |
            */
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            /*
            |--------------------------------------------------------------------------
            | vehicle_type_id
            |--------------------------------------------------------------------------
            |
            | Guarda el tipo de vehículo.
            | Ejemplo:
            | auto, moto, camioneta, etc.
            |
            | Esta columna será una llave foránea hacia la tabla relacionada
            | con los tipos de vehículo.
            | Laravel intentará inferir la tabla automáticamente.
            |
            | Si se elimina el registro relacionado, también se elimina este vehículo.
            */
            $table->foreignId('vehicle_type_id')->constrained()->onDelete('cascade');

            /*
            |--------------------------------------------------------------------------
            | vehicle_status_id
            |--------------------------------------------------------------------------
            |
            | Guarda el estado del vehículo.
            | Ejemplo:
            | activo, en reparación, vendido, etc.
            |
            | También es una llave foránea con eliminación en cascada.
            */
            $table->foreignId('vehicle_status_id')->constrained()->onDelete('cascade');

            /*
            |--------------------------------------------------------------------------
            | plates
            |--------------------------------------------------------------------------
            |
            | Aquí se guardan las placas del vehículo.
            | Se usa string porque normalmente contiene letras, números y guiones.
            */
            $table->string('plates');

            /*
            |--------------------------------------------------------------------------
            | serial_number
            |--------------------------------------------------------------------------
            |
            | Guarda el número de serie o VIN del vehículo.
            | También se usa string porque puede contener letras y números.
            */
            $table->string('serial_number');

            /*
            |--------------------------------------------------------------------------
            | gasoline_type
            |--------------------------------------------------------------------------
            |
            | Guarda el tipo de gasolina que usa el vehículo.
            | Ejemplo:
            | regular, premium, diesel.
            */
            $table->string('gasoline_type');

            /*
            |--------------------------------------------------------------------------
            | oil_type
            |--------------------------------------------------------------------------
            |
            | Guarda el tipo de aceite que usa el vehículo.
            | Ejemplo:
            | 5W-30, 10W-40, etc.
            */
            $table->string('oil_type');

            /*
            |--------------------------------------------------------------------------
            | model_name
            |--------------------------------------------------------------------------
            |
            | Guarda el nombre comercial o modelo del vehículo.
            | Ejemplo:
            | Civic, Corolla, Versa, etc.
            */
            $table->string('model_name');

            /*
            |--------------------------------------------------------------------------
            | photo
            |--------------------------------------------------------------------------
            |
            | Guarda la ruta o nombre del archivo de la foto del vehículo.
            | nullable() significa que este campo puede ir vacío.
            |
            | Esto es útil si todavía no se ha subido una imagen.
            */
            $table->string('photo')->nullable();

            /*
            |--------------------------------------------------------------------------
            | model_year
            |--------------------------------------------------------------------------
            |
            | Guarda el año del modelo del vehículo.
            | Se usa year() porque Laravel crea una columna pensada para años.
            |
            | Ejemplo:
            | 2018, 2020, 2024
            */
            $table->year('model_year');

            /*
            |--------------------------------------------------------------------------
            | timestamps()
            |--------------------------------------------------------------------------
            |
            | Crea automáticamente dos columnas:
            | - created_at: cuándo se creó el registro
            | - updated_at: cuándo se actualizó por última vez
            |
            | Laravel las usa mucho para saber cuándo nació y cambió un registro.
            */
            $table->timestamps();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Método down()
    |--------------------------------------------------------------------------
    |
    | Este método se ejecuta cuando corres:
    | php artisan migrate:rollback
    |
    | Su función es revertir la migración.
    | Aquí borra la tabla "vehicles".
    |
    */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};