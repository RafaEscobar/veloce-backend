<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/*
|--------------------------------------------------------------------------
| Modelo User
|--------------------------------------------------------------------------
|
| Este modelo "User" representa la tabla "users".
|
| Gracias a Eloquent (el ORM de Laravel), este modelo permite:
| - crear usuarios
| - consultar usuarios
| - actualizar usuarios
| - eliminar usuarios
|
| En pocas palabras:
| este archivo es la forma orientada a objetos de trabajar con
| los registros de la tabla "users".
|
*/

class User extends Authenticatable
{
    /*
    |--------------------------------------------------------------------------
    | Traits
    |--------------------------------------------------------------------------
    |
    | Los traits agregan funcionalidades reutilizables a la clase.
    |
    */

    use HasApiTokens, HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | HasFactory
    |--------------------------------------------------------------------------
    |
    | Permite usar factories.
    |
    | Las factories sirven para crear datos falsos automáticamente.
    | Muy útil para pruebas, seeds y desarrollo.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Notifiable
    |--------------------------------------------------------------------------
    |
    | Permite enviar notificaciones al usuario.
    |
    | Por ejemplo:
    | - correos
    | - notificaciones en base de datos
    | - SMS (si se configura)
    |
    */

    /*
    |--------------------------------------------------------------------------
    | $fillable
    |--------------------------------------------------------------------------
    |
    | Define qué columnas pueden llenarse de forma masiva,
    | esto protege tu aplicación de asignaciones peligrosas.
    |
    | Ejemplo:
    | User::create([
    |     'name' => 'Rafael',
    |     'email' => 'rafa@email.com',
    |     'password' => '123456'
    | ]);
    |
    | Solo los campos listados aquí podrán asignarse así.
    |
    */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /*
    |--------------------------------------------------------------------------
    | $hidden
    |--------------------------------------------------------------------------
    |
    | Define qué atributos NO deben mostrarse cuando el modelo
    | se convierte a:
    |
    | - array
    | - JSON
    |
    | Esto es importante para proteger información sensible.
    |
    */
    protected $hidden = [
        /*
        | La contraseña nunca debería exponerse.
        */
        'password',

        /*
        | Token usado para recordar sesión.
        | Tampoco conviene exponerlo.
        */
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | casts()
    |--------------------------------------------------------------------------
    |
    | Los casts transforman automáticamente ciertos atributos
    | cuando Laravel los obtiene o los guarda.
    |
    */
    protected function casts(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | email_verified_at => datetime
            |--------------------------------------------------------------------------
            |
            | Hace que este campo se convierta automáticamente
            | a una instancia de fecha.
            |
            | Así puedes hacer cosas como:
            | $user->email_verified_at->format('d/m/Y');
            |
            */
            'email_verified_at' => 'datetime',

            /*
            |--------------------------------------------------------------------------
            | password => hashed
            |--------------------------------------------------------------------------
            |
            | Cuando guardas una contraseña,
            | Laravel automáticamente la encripta (hashea).
            |
            | Ejemplo:
            | User::create([
            |     'password' => '123456'
            | ]);
            |
            | Laravel NO guarda "123456" directamente.
            | Guarda una versión segura e irreversible.
            |
            */
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relación vehicles()
    |--------------------------------------------------------------------------
    |
    | Este método define una relación entre tablas.
    |
    | Un usuario puede tener muchos vehículos.
    |
    | En base de datos esto significa que la tabla "vehicles"
    | tiene una columna llamada "user_id".
    |
    */
    public function vehicles(): HasMany
    {
        /*
        |--------------------------------------------------------------------------
        | hasMany()
        |--------------------------------------------------------------------------
        |
        | Le dice a Laravel:
        |
        | "Este usuario tiene muchos registros relacionados
        | en la tabla vehicles"
        |
        | Vehicle::class indica el modelo relacionado.
        |
        */
        return $this->hasMany(Vehicle::class);
    }
}
