# Ciclo de vida de los datos

## Creación del esquema

Las migraciones crean primero infraestructura Laravel (`users`, recuperación, sesiones, caché y colas), después catálogos, vehículos y registros dependientes, y finalmente tokens Sanctum. El orden de timestamps permite resolver todas las FKs.

Flujo reproducible previsto:

```text
migrate
  → tablas de infraestructura
  → catálogos
  → vehicles
  → gasoline_refills / maintenances / reminder_priorities / reminders
  → personal_access_tokens
seed
  → VehicleTypeSeeder
  → VehicleStatusSeeder
  → ReminderPrioritySeeder
```

`DatabaseSeeder` solo llama a esos tres seeders. No crea usuarios ni datos operativos.

## Escritura desde la aplicación

- Registro crea `User` manualmente y emite un token personal.
- Vehicle fuerza `user_id`; una foto se guarda bajo `vehicles` en el disco `public`.
- Cargas, mantenimientos y recordatorios validan una FK existente y el controlador confirma que el vehículo pertenezca al usuario antes de crear.
- Updates usan asignación masiva con el resultado validado. En mantenimientos y recordatorios puede cambiarse el vehículo tras comprobar ownership; carga no acepta ese campo actualmente.
- Eloquent mantiene `created_at` y `updated_at` en todas las tablas de dominio.

No hay transacciones explícitas. En operaciones que combinan archivo y fila (`VehicleController`) el filesystem y la base de datos no forman una unidad atómica.

## Lectura y serialización

Los listados de negocio consultan solo vehículos del usuario, ordenan por `created_at` descendente y paginan. Los catálogos son globales; tipos pagina y estados/prioridades cargan todo. Resources convierten casts y seleccionan campos; consulta [Modelo de dominio](03-domain-model.md) para pérdidas o diferencias de representación.

## Actualización de fotos

1. El Request valida una imagen de hasta 2 MB.
2. Si ya existe una foto, el controlador la elimina del disco `public`.
3. Guarda la nueva foto.
4. Actualiza la fila con la nueva ruta.

No hay transacción compensatoria: un fallo entre pasos puede dejar la fila apuntando a un archivo eliminado o un archivo nuevo sin referencia. Esta es una característica del flujo actual, no una corrección propuesta.

## Borrado

Todos los borrados de dominio son físicos. `VehicleController::destroy` elimina primero la foto y luego la fila. Las FKs propagan en cascada según esta cadena:

```text
User / VehicleType / VehicleStatus
  → Vehicle
      → GasolineRefill
      → Maintenance
      → Reminder ← ReminderPriority
```

Consecuencias:

- borrar usuario elimina todos sus datos de vehículo;
- borrar tipo o estado elimina los vehículos que lo usan, no restringe ni pone FK a null;
- borrar prioridad elimina sus recordatorios;
- las cascadas de base de datos no ejecutan la limpieza de `Storage`, por lo que pueden dejar fotos huérfanas;
- no hay recuperación mediante soft delete.

## Seeders y catálogos

Los seeders son idempotentes respecto del texto exacto mediante `updateOrCreate`. Como el esquema no declara unicidad, duplicados creados por otras vías siguen siendo posibles. Cambiar ortografía o capitalización crea otro registro en vez de renombrar el anterior.

Ejecutar `DatabaseSeeder` carga los valores canónicos documentados en `03-domain-model.md`. El código no contiene endpoints de escritura para catálogos.

## Factories

| Factory | Estado útil |
| --- | --- |
| `UserFactory` | Desalineada: falta `last_name` y usa `email_verified_at` inexistente |
| `VehicleTypeFactory` | Existe; sus textos no coinciden totalmente con el seeder |
| `VehicleStatusFactory` | Existe; capitalización distinta del seeder |
| Resto del dominio | No existen factories |

Hasta corregir estas diferencias, las factories no forman una base fiable y completa para pruebas integradas. Este documento no las modifica.

## Cambios de esquema: puntos de control

Antes de cambiar una entidad, contrasta:

1. migración nueva y compatibilidad con datos existentes;
2. nulabilidad, default, precisión, índices y FKs;
3. `$fillable`, `$hidden`, casts y relaciones del modelo;
4. Requests de store y update;
5. controlador y comprobaciones de ownership;
6. Resource/Collection y contrato Scribe;
7. factories, seeders y pruebas;
8. cascadas y archivos externos.

No edites una migración ya aplicada en entornos compartidos; añade una migración evolutiva. La guía local y comandos exactos se completarán en el paso 6 del plan.

## Tablas de infraestructura

Las tablas `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs` y `personal_access_tokens` pertenecen a Laravel/Sanctum. Su existencia configura capacidades, pero no demuestra uso activo de recuperación, sesiones web, caché o colas por código de negocio.

Última verificación: 2026-08-21.

Fuentes consultadas: `database/migrations/`, `database/seeders/`, `database/factories/`, modelos y controladores API.
