# Inventario de componentes

Este inventario responde “¿qué clase cumple cada responsabilidad?”. Para el flujo entre capas consulta [Arquitectura](../02-architecture.md). No sustituye el mapa endpoint por endpoint previsto para el paso 4.

## Controladores API

| Controlador | Acciones | Colaboradores principales |
| --- | --- | --- |
| `AuthController` | `register`, `login`, `logout` | Requests de auth, `User`, `UserResource`, Auth, Hash y Sanctum |
| `VehicleController` | `index`, `store`, `show`, `update`, `destroy` | Requests/Resources de vehículo, `VehiclePolicy`, `Vehicle`, `Storage` |
| `GasolineRefillController` | `index`, `store`, `update`, `destroy` | Requests/Resources de carga, policy, modelo y vehículos del usuario |
| `MaintenanceController` | `index`, `store`, `update`, `destroy` | Requests/Resources de mantenimiento, policy, modelo y vehículos del usuario |
| `ReminderController` | `index`, `store`, `update`, `destroy` | Requests/Resources de recordatorio, policy, modelo, prioridad y vehículo |
| `VehicleTypeController` | `index` | `VehicleType`, `VehicleTypeCollection` |
| `VehicleStatusController` | `index` | `VehicleStatus`, `VehicleStatusCollection` |
| `ReminderPriorityController` | `index` | `ReminderPriority`, `ReminderPriorityCollection` |

Todos viven en `app/Http/Controllers/Api/`. `app/Http/Controllers/Controller.php` aporta los traits de autorización y validación de Laravel.

## Form Requests

| Clase | Uso | Observación |
| --- | --- | --- |
| `StoreRegisterRequest` | Registro | Identidad y contraseña |
| `LoginRequest` | Login | Forma básica de credenciales |
| `StoreVehicleRequest` / `UpdateVehicleRequest` | Crear/actualizar vehículo | Catálogos, campos parciales e imagen |
| `StoreGasolineRefillRequest` | Crear carga | Vehículo, monto y litros |
| `UpdateGasolineRefillRequest` | Actualizar carga | No admite `vehicle_id`, aunque el controlador conserva una comprobación defensiva |
| `StoreMaintenanceRequest` / `UpdateMaintenanceRequest` | Crear/actualizar mantenimiento | Update admite cambiar vehículo |
| `StoreReminderRequest` / `UpdateReminderRequest` | Crear/actualizar recordatorio | Vehículo y prioridad; update admite cambiar ambos |

Las diez clases viven en `app/Http/Requests/`, retornan `true` en `authorize()` y concentran reglas y mensajes. La autorización de negocio no ocurre allí.

## Resources y Collections

| Entidad | Resource | Collection | Forma relevante |
| --- | --- | --- | --- |
| Usuario | `UserResource` | — | Respuesta de autenticación |
| Vehículo | `VehicleResource` | `VehicleCollection` | Foto como URL, relaciones condicionales y paginación |
| Carga | `GasolineRefillResource` | `GasolineRefillCollection` | Listado paginado |
| Mantenimiento | `MaintenanceResource` | `MaintenanceCollection` | Listado paginado |
| Recordatorio | `ReminderResource` | `ReminderCollection` | Prioridad anidada y listado paginado |
| Tipo | `VehicleTypeResource` | `VehicleTypeCollection` | Controlador paginado |
| Estado | `VehicleStatusResource` | `VehicleStatusCollection` | Controlador no paginado |
| Prioridad | `ReminderPriorityResource` | `ReminderPriorityCollection` | Controlador no paginado |

Las 15 clases viven en `app/Http/Resources/`. Las cuatro Collections de negocio reemplazan metadatos estándar por `firstPage`, `lastPage`, `total`, `currentPage` y `per_page`.

## Modelos

| Modelo | Papel | Relaciones declaradas |
| --- | --- | --- |
| `User` | Cuenta autenticable | muchos vehículos |
| `Vehicle` | Agregado principal | usuario, tipo, estado, cargas, mantenimientos y recordatorios |
| `GasolineRefill` | Carga | pertenece a vehículo |
| `Maintenance` | Mantenimiento | pertenece a vehículo |
| `Reminder` | Aviso | pertenece a vehículo y prioridad |
| `VehicleType` | Catálogo | muchos vehículos |
| `VehicleStatus` | Catálogo | muchos vehículos |
| `ReminderPriority` | Catálogo | muchos recordatorios |

Campos, casts, claves y cascadas corresponden al paso 3.

## Policies

| Policy | Modelo | Regla estructural |
| --- | --- | --- |
| `VehiclePolicy` | `Vehicle` | `viewAny`/`create` permiten; instancias comparan `user_id` |
| `GasolineRefillPolicy` | `GasolineRefill` | Instancias siguen `vehicle.user_id` |
| `MaintenancePolicy` | `Maintenance` | Instancias siguen `vehicle.user_id` |
| `ReminderPolicy` | `Reminder` | Instancias siguen `vehicle.user_id` |

Viven en `app/Policies/` y se descubren por convención. Catálogos y autenticación no tienen policy. La matriz completa corresponde al paso 5.

## Arranque e infraestructura local

| Componente | Función actual |
| --- | --- |
| `bootstrap/app.php` | Rutas, `/up`, proxies y excepciones predeterminadas |
| `bootstrap/providers.php` | Declara `AppServiceProvider` |
| `AppServiceProvider` | Vacío; sin bindings ni hooks |
| `routes/api.php` | Auth, recursos y catálogos |
| `routes/web.php` | `welcome` en `/` |
| `routes/console.php` | Consola del esqueleto |
| `config/sanctum.php` | Autenticación API |
| `config/scribe.php` | Documentación API |
| `config/filesystems.php` | Disco de fotos |
| Resto de `config/` | Configuración estándar de aplicación e infraestructura |

## Factories, seeders y pruebas

- Factories: `UserFactory`, `VehicleTypeFactory`, `VehicleStatusFactory`.
- Seeders: `DatabaseSeeder`, `VehicleTypeSeeder`, `VehicleStatusSeeder`, `ReminderPrioritySeeder`.
- Pruebas: `tests/Feature/ExampleTest.php` y `tests/Unit/ExampleTest.php`, más bootstrap de Pest; no cubren el dominio inventariado.

## Código mantenido, publicado y estándar

| Categoría | Ubicaciones |
| --- | --- |
| Negocio local | Ruta API, controladores API, Requests, Resources, modelos, policies, migraciones y seeders de dominio |
| Scribe | `config/scribe.php`, `.scribe/`, `resources/views/scribe/`, `resources/views/vendor/scribe/`, `public/vendor/scribe/` |
| Esqueleto Laravel | Controlador base, provider, `welcome`, configuraciones, migraciones de infraestructura y pruebas Example |
| Frontend mínimo | CSS/JS, `vite.config.js`, Tailwind y Vite |

Las vistas y assets bajo rutas `vendor/scribe` son publicaciones del paquete: antes de editarlos, confirma si procede configurar, sobrescribir deliberadamente o regenerar.

## Componentes no encontrados

No hay clases locales de servicios, repositorios, DTOs, actions, jobs, eventos/listeners, observers, middleware propio, notificaciones de dominio ni comandos Artisan propios. Es una observación, no una clasificación como defecto.

Última verificación: 2026-08-24.

Fuentes consultadas: listados completos de `app/`, `bootstrap/`, `routes/`, `config/`, `database/factories/`, `database/seeders/`, `tests/`, `resources/`, `.scribe/` y `public/vendor/scribe/`.
