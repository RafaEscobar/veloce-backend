# Modelo de dominio

## Mapa de entidades

```text
User 1 ── N Vehicle
              ├── N GasolineRefill
              ├── N Maintenance
              └── N Reminder N ── 1 ReminderPriority
              │
              ├── N ── 1 VehicleType
              └── N ── 1 VehicleStatus
```

`Vehicle` es el agregado que conecta la cuenta con todos los registros operativos. `VehicleType`, `VehicleStatus` y `ReminderPriority` son catálogos globales, no pertenecen a un usuario.

## Ownership

| Entidad | Pertenencia | Evidencia |
| --- | --- | --- |
| `User` | Identidad raíz | Modelo autenticable y tokens Sanctum |
| `Vehicle` | Directa: `vehicles.user_id` | FK, relación `User::vehicles()` y `VehiclePolicy` |
| `GasolineRefill` | Indirecta por `vehicle_id` | Relación y policy comparan `vehicle.user_id` |
| `Maintenance` | Indirecta por `vehicle_id` | Relación y policy comparan `vehicle.user_id` |
| `Reminder` | Indirecta por `vehicle_id` | Relación y policy comparan `vehicle.user_id` |
| Catálogos | Global | Sin `user_id`; endpoints compartidos de solo lectura |

Las reglas HTTP completas se documentarán en el paso 5. La base de datos garantiza referencias, pero no garantiza por sí sola que una petición use un vehículo del usuario autenticado.

## Entidades

### User

Tabla `users`. Campos persistidos: `id`, `name`, `last_name`, `email` único, `password`, `remember_token` nullable y timestamps. No existe `email_verified_at` en la migración actual.

El modelo oculta `password` y `remember_token`, castea `password` como `hashed` y declara `email_verified_at` como `datetime`. `$fillable` contiene `name`, `email` y `password`, pero no `last_name`. `UserResource` publica `id`, `name`, `last_name` y `email`.

### Vehicle

Tabla `vehicles`. Campos requeridos por esquema: `name`, `user_id`, `vehicle_type_id`, `vehicle_status_id`, `plates`, `serial_number`, `gasoline_type`, `oil_type`, `model_name` y `model_year`. Solo `photo` es nullable. Todos los campos de negocio y FKs están en `$fillable`; `model_year` se castea a entero.

Pertenece a usuario, tipo y estado; tiene muchas cargas, mantenimientos y recordatorios. `VehicleResource` no expone directamente los tres IDs de relación, pero puede incluir relaciones cargadas y convierte `photo` en URL.

### GasolineRefill

Tabla `gasoline_refills`. Requiere `vehicle_id`, `amount decimal(10,2)` y `liters decimal(8,2)`; `date` y `gas_station` son nullables. El modelo castea el ID a entero, los decimales a strings con dos posiciones y `date` a fecha. Pertenece a un vehículo.

### Maintenance

Tabla `maintenances`. Requiere vehículo, nombre y fecha. `cost decimal(10,2)` y `notes` son nullables; `is_reminder_enabled` es booleano, no nullable y predetermina `false`. El modelo castea fecha, costo y booleano. La bandera solo se persiste: no se encontró automatización que cree un `Reminder`.

### Reminder

Tabla `reminders`. Requiere vehículo, nombre, fecha/hora y prioridad; `description` es nullable. El modelo castea `date` a `datetime` y pertenece a vehículo y prioridad. `ReminderResource` expone la prioridad cargada pero formatea `date` como `Y-m-d`, descartando la hora en la respuesta.

### Catálogos

| Modelo/tabla | Campo | Valores del seeder |
| --- | --- | --- |
| `VehicleType` / `vehicle_types` | `type` | Vehículo, Motocicleta, Cuatrimoto, Camioneta, Camión |
| `VehicleStatus` / `vehicle_statuses` | `status` | Activo, Vendido, Suspendido |
| `ReminderPriority` / `reminder_priorities` | `priority` | Crítica, Alta, Media, Baja, Opcional |

Los campos de catálogo son strings requeridos, pero no tienen índice `unique`. Los seeders usan `updateOrCreate` por texto.

## Relaciones y borrado

| Padre eliminado | FK | Efecto de base de datos |
| --- | --- | --- |
| Usuario | `vehicles.user_id` | Elimina vehículos; estos eliminan cargas, mantenimientos y recordatorios |
| Tipo de vehículo | `vehicles.vehicle_type_id` | Elimina vehículos clasificados y sus hijos |
| Estado de vehículo | `vehicles.vehicle_status_id` | Elimina vehículos en ese estado y sus hijos |
| Vehículo | `*.vehicle_id` | Elimina cargas, mantenimientos y recordatorios |
| Prioridad | `reminders.reminder_priority_id` | Elimina recordatorios con esa prioridad |

No hay soft deletes. Las cascadas son físicas y no ejecutan `VehicleController::destroy`; una foto del disco público puede quedar huérfana si el vehículo desaparece por cascada o por un borrado Eloquent fuera de ese controlador.

## Invariantes verificadas

- Cada vehículo tiene exactamente un usuario, tipo y estado en base de datos.
- Cada carga, mantenimiento y recordatorio tiene exactamente un vehículo.
- Cada recordatorio tiene exactamente una prioridad.
- Email es único; los valores de catálogo, placas y serial no lo son.
- Cantidades monetarias/litros tienen escala de dos decimales en base de datos.
- Requests impiden montos, litros y costos negativos, pero esa restricción no existe como `CHECK` en migraciones.
- Ownership se impone en aplicación mediante policies y consultas, no mediante una estructura multitenant en base de datos.

## Discrepancias confirmadas

| Capas | Discrepancia | Impacto posible |
| --- | --- | --- |
| Migración de vehículos ↔ Requests | `plates`, `serial_number`, `gasoline_type`, `oil_type`, `model_name` y `model_year` son NOT NULL, pero store/update permiten omitirlos o enviarlos nulos | Error de integridad al crear o actualizar |
| Requests de vehículo | Store limita `plates` a 24; update permite 255 | Contrato distinto según operación; la columna string admite hasta 255 |
| Migración de users ↔ `User` | No existe `email_verified_at`, pero el modelo lo castea | Atributo inefectivo/inexistente |
| Migración de users ↔ `UserFactory` | Factory omite `last_name` requerido y escribe `email_verified_at` inexistente | La factory no puede crear usuarios contra el esquema actual |
| `User` ↔ registro | `$fillable` omite `last_name`; el controlador lo asigna propiedad por propiedad | El flujo actual funciona, pero `User::create()` con apellido lo descartaría/protegería |
| `Reminder` ↔ `ReminderResource` | Persistencia/cast conservan hora; Resource devuelve solo fecha | Pérdida de precisión en el contrato JSON |
| Catálogos ↔ factories | Factory usa `Vehiculo` sin acento y estados `vendido`/`suspendido` en minúsculas, distintos de seeders | Datos de prueba no equivalentes a datos base |
| Modelos con `HasFactory` ↔ factories | No hay factories para Vehicle, GasolineRefill, Maintenance, Reminder ni ReminderPriority | Preparación incompleta de pruebas de dominio |
| Update de carga ↔ controlador | Request no admite `vehicle_id`, pero controlador contempla cambiarlo | Rama defensiva actualmente inalcanzable mediante datos validados |

Estas discrepancias se documentan como evidencia y no se corrigen dentro del paso 3.

Última verificación: 2026-08-21.

Fuentes consultadas: migraciones de `database/migrations/`, `app/Models/`, `app/Http/Requests/`, `app/Http/Resources/`, `app/Policies/`, controladores API, factories y seeders.
