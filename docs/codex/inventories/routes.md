# Inventario de rutas API

Inventario expandido desde `routes/api.php`. Base: `/api`. `Auth` significa `auth:sanctum`; los cuatro controladores de recursos añaden autorización de policy mediante `authorizeResource`.

## Autenticación

| Método | URI | Nombre | Middleware adicional | Acción | Request | Policy/modelo | Salida | Scribe |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| POST | `/api/register` | — | `throttle:3,1` | `AuthController::register` | `StoreRegisterRequest` | — / `User` | 201 `{user: UserResource, token}` | Sí |
| POST | `/api/login` | — | `throttle:5,1` | `AuthController::login` | `LoginRequest` | — / `User` | 200 `{user: UserResource, token}` | Sí |
| POST | `/api/logout` | — | Auth | `AuthController::logout` | `Request` | — / `User`, tokens | 200 `{message}` | Sí |

## Vehículos

| Método | URI | Nombre esperado | Middleware | Acción | Request | Policy | Modelo/salida | Scribe |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/api/vehicles` | `vehicles.index` | Auth | `VehicleController::index` | `Request` | `viewAny` | `Vehicle` → `VehicleCollection` | No |
| POST | `/api/vehicles` | `vehicles.store` | Auth | `VehicleController::store` | `StoreVehicleRequest` | `create` | `Vehicle` → `VehicleResource` | Sí; auth incorrecta |
| GET | `/api/vehicles/{vehicle}` | `vehicles.show` | Auth | `VehicleController::show` | — | `view` | `Vehicle` → `VehicleResource` | No |
| PUT, PATCH | `/api/vehicles/{vehicle}` | `vehicles.update` | Auth | `VehicleController::update` | `UpdateVehicleRequest` | `update` | `Vehicle` → `VehicleResource` | Sí; auth incorrecta |
| DELETE | `/api/vehicles/{vehicle}` | `vehicles.destroy` | Auth | `VehicleController::destroy` | — | `delete` | `Vehicle` → 200 `{message}` | Sí; auth incorrecta |

## Cargas de gasolina

| Método | URI | Nombre esperado | Middleware | Acción | Request | Policy | Modelo/salida | Scribe |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/api/gasoline-refills` | `gasoline-refills.index` | Auth | `GasolineRefillController::index` | `Request` | `viewAny` | `GasolineRefill` → Collection | No |
| POST | `/api/gasoline-refills` | `gasoline-refills.store` | Auth | `GasolineRefillController::store` | `StoreGasolineRefillRequest` | `create` | `GasolineRefill` → Resource | Sí |
| PUT, PATCH | `/api/gasoline-refills/{gasoline_refill}` | `gasoline-refills.update` | Auth | `GasolineRefillController::update` | `UpdateGasolineRefillRequest` | `update` | `GasolineRefill` → Resource | Sí |
| DELETE | `/api/gasoline-refills/{gasoline_refill}` | `gasoline-refills.destroy` | Auth | `GasolineRefillController::destroy` | — | `delete` | `GasolineRefill` → 200 `{message}` | Sí |

## Mantenimientos

| Método | URI | Nombre esperado | Middleware | Acción | Request | Policy | Modelo/salida | Scribe |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/api/maintenances` | `maintenances.index` | Auth | `MaintenanceController::index` | `Request` | `viewAny` | `Maintenance` → Collection | No |
| POST | `/api/maintenances` | `maintenances.store` | Auth | `MaintenanceController::store` | `StoreMaintenanceRequest` | `create` | `Maintenance` → Resource | Sí |
| PUT, PATCH | `/api/maintenances/{maintenance}` | `maintenances.update` | Auth | `MaintenanceController::update` | `UpdateMaintenanceRequest` | `update` | `Maintenance` → Resource | Sí |
| DELETE | `/api/maintenances/{maintenance}` | `maintenances.destroy` | Auth | `MaintenanceController::destroy` | — | `delete` | `Maintenance` → 200 `{message}` | Sí |

## Recordatorios

| Método | URI | Nombre esperado | Middleware | Acción | Request | Policy | Modelo/salida | Scribe |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/api/reminders` | `reminders.index` | Auth | `ReminderController::index` | `Request` | `viewAny` | `Reminder` → Collection | No |
| POST | `/api/reminders` | `reminders.store` | Auth | `ReminderController::store` | `StoreReminderRequest` | `create` | `Reminder` → Resource | Sí |
| PUT, PATCH | `/api/reminders/{reminder}` | `reminders.update` | Auth | `ReminderController::update` | `UpdateReminderRequest` | `update` | `Reminder` → Resource | Sí |
| DELETE | `/api/reminders/{reminder}` | `reminders.destroy` | Auth | `ReminderController::destroy` | — | `delete` | `Reminder` → 200 `{message}` | Sí |

## Catálogos

| Método | URI | Nombre | Middleware | Acción | Request/policy | Modelo/salida | Scribe |
| --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/api/vehicle-types` | — | Auth | `VehicleTypeController::index` | — | `VehicleType::paginate` → Collection | No |
| GET | `/api/vehicle-statuses` | — | Auth | `VehicleStatusController::index` | — | `VehicleStatus::all` → Collection | No |
| GET | `/api/reminder-priorities` | — | Auth | `ReminderPriorityController::index` | — | `ReminderPriority::all` → Collection | No |

## Consultas, orden y paginación

| Endpoints | Scope/filtro fijo | Orden | Paginación |
| --- | --- | --- | --- |
| Vehículos index | Relación del usuario autenticado | `created_at DESC` | `paginate()` predeterminado; query `page` |
| Cargas/mantenimientos/recordatorios index | `vehicle_id IN` vehículos del usuario | `created_at DESC` | `paginate()` predeterminado; query `page` |
| Tipos | Ninguno | Orden DB no explícito | `paginate()`; query `page` |
| Estados/prioridades | Ninguno | Orden DB no explícito | No; `all()` |

No se implementan otros query parameters. En particular no hay búsqueda, filtros de fecha/vehículo/prioridad, selector de orden ni `per_page` controlado por cliente.

## Códigos de éxito y error

- Éxito: registro 201; resto 200 por código/defaults actuales.
- Errores comunes esperados: 401 autenticación, 403 policy, 404 binding/ownership mediante `findOrFail`, 422 validación y 429 throttle.
- El formato exacto de errores es el predeterminado de Laravel porque `bootstrap/app.php` no lo personaliza.

## Limitación de verificación

Los nombres marcados “esperado” y los parámetros resource se derivan de las convenciones de `Route::apiResource`. No pudieron confirmarse con `php artisan route:list`: la instalación actual no carga `Laravel\\Sanctum\\Sanctum`. Las rutas manuales no tienen `->name()` explícito.

Última verificación: 2026-08-21.

Fuentes consultadas: `routes/api.php`, controladores, Requests, Resources/Collections, policies y `.scribe/endpoints/*.yaml`.
