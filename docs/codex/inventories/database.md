# Inventario de base de datos

La fuente primaria es `database/migrations/`. `REQ` significa NOT NULL; `NULL` admite null. Todos los `id()` son claves primarias bigint autoincrementales.

## Tablas de negocio

| Tabla | Columnas | Claves y borrado |
| --- | --- | --- |
| `users` | `id`; `name` REQ string; `last_name` REQ string; `email` REQ string; `password` REQ string; `remember_token` NULL; timestamps | `email` unique |
| `vehicle_types` | `id`; `type` REQ string; timestamps | Sin unique en `type` |
| `vehicle_statuses` | `id`; `status` REQ string; timestamps | Sin unique en `status` |
| `vehicles` | `id`; `name` REQ string; `user_id`, `vehicle_type_id`, `vehicle_status_id` REQ bigint; `plates`, `serial_number`, `gasoline_type`, `oil_type`, `model_name` REQ string; `photo` NULL string; `model_year` REQ year; timestamps | Tres FKs con cascade delete |
| `gasoline_refills` | `id`; `vehicle_id` REQ bigint; `amount` REQ decimal(10,2); `liters` REQ decimal(8,2); `date` NULL date; `gas_station` NULL string; timestamps | FK vehículo con cascade delete |
| `maintenances` | `id`; `vehicle_id` REQ bigint; `name` REQ string; `date` REQ date; `cost` NULL decimal(10,2); `is_reminder_enabled` REQ boolean default false; `notes` NULL text; timestamps | FK vehículo con cascade delete |
| `reminder_priorities` | `id`; `priority` REQ string; timestamps | Sin unique en `priority` |
| `reminders` | `id`; `vehicle_id` REQ bigint; `name` REQ string; `description` NULL text; `date` REQ datetime; `reminder_priority_id` REQ bigint; timestamps | FKs a vehículo y prioridad con cascade delete |

Laravel usa longitud predeterminada para `string` (normalmente 255 según driver/configuración). La migración no declara índices adicionales sobre campos de consulta más allá de los creados por claves foráneas.

## Relaciones

| Origen | Destino | Cardinalidad Eloquent | FK |
| --- | --- | --- | --- |
| `users` | `vehicles` | 1:N | `vehicles.user_id` |
| `vehicle_types` | `vehicles` | 1:N | `vehicles.vehicle_type_id` |
| `vehicle_statuses` | `vehicles` | 1:N | `vehicles.vehicle_status_id` |
| `vehicles` | `gasoline_refills` | 1:N | `gasoline_refills.vehicle_id` |
| `vehicles` | `maintenances` | 1:N | `maintenances.vehicle_id` |
| `vehicles` | `reminders` | 1:N | `reminders.vehicle_id` |
| `reminder_priorities` | `reminders` | 1:N | `reminders.reminder_priority_id` |

Todas las FKs de negocio son obligatorias y usan cascade delete.

## Tablas de infraestructura

| Tabla | Propósito y claves relevantes |
| --- | --- |
| `password_reset_tokens` | Token por email; `email` es PK, `created_at` nullable |
| `sessions` | Sesiones de base de datos; `id` PK, `user_id` nullable indexado sin FK, actividad indexada |
| `cache` | Entradas de caché; `key` PK, expiración indexada |
| `cache_locks` | Locks; `key` PK, expiración indexada |
| `jobs` | Cola database; `id` PK, `queue` indexada |
| `job_batches` | Lotes de jobs; `id` string PK |
| `failed_jobs` | Fallos; `id` PK y `uuid` unique |
| `personal_access_tokens` | Tokens Sanctum polimórficos; morph index, token unique y expiración indexada |

## Casts y representación

| Modelo | Casts explícitos |
| --- | --- |
| `User` | `email_verified_at: datetime` (columna ausente); `password: hashed` |
| `Vehicle` | `model_year: integer` |
| `GasolineRefill` | `vehicle_id: integer`, `amount/liters: decimal:2`, `date: date` |
| `Maintenance` | `date: date`, `cost: decimal:2`, `is_reminder_enabled: boolean` |
| `Reminder` | `date: datetime` |
| Catálogos | Ninguno |

## Índices y unicidad de negocio

Solo `users.email` tiene unicidad de dominio declarada. No hay unique para textos de catálogo, placas o serial del vehículo. No hay checks para valores no negativos ni rango de `model_year`; esas restricciones dependen de Requests, y el rango del año ni siquiera está limitado allí.

## Fuentes de contraste

Las discrepancias entre este esquema, modelos, Requests, Resources y factories están centralizadas en [Modelo de dominio](../03-domain-model.md#discrepancias-confirmadas). El ciclo de migración, seed y borrado está en [Ciclo de vida](../06-data-lifecycle.md).

Última verificación: 2026-08-24.

Fuentes consultadas: las once migraciones actuales, los ocho modelos, Requests, Resources, factories y seeders.
