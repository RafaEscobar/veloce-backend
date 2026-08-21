# Arquitectura de Veloce

## Vista general

Veloce sigue una arquitectura Laravel directa y centrada en recursos HTTP. Las rutas envían la petición a controladores; los Form Requests validan la entrada; las policies y consultas del controlador limitan el acceso; Eloquent lee o persiste; y los Resources/Collections construyen la respuesta JSON.

No existe una capa propia de servicios o repositorios. Los controladores coordinan las operaciones y llaman directamente a modelos, relaciones Eloquent, Sanctum y `Storage`. Esto describe el diseño actual; no implica que deban añadirse capas.

```text
cliente
  → ruta (`routes/api.php`)
  → middleware (throttle o `auth:sanctum`)
  → route model binding, cuando hay un recurso en la URI
  → autorización de recurso (`authorizeResource` → policy)
  → Form Request (authorize + rules), cuando la acción recibe datos
  → controlador
      → consulta/relación Eloquent y comprobaciones de ownership
      → almacenamiento público, solo para fotos de vehículos
  → JsonResource o ResourceCollection
  → respuesta JSON
```

El orden interno exacto lo ejecuta Laravel. Para una modificación concreta deben revisarse la firma del controlador y el middleware que añade `authorizeResource`; el esquema es un mapa de responsabilidades, no una traza completa del framework.

## Arranque y entrada HTTP

`bootstrap/app.php` registra las rutas web, API y de consola, el health check `/up`, confianza en proxies reenviados para cualquier proxy (`*`) y manejo de excepciones sin personalización local. Laravel sirve las rutas API bajo `/api`.

`bootstrap/providers.php` registra únicamente `AppServiceProvider`. Sus métodos `register()` y `boot()` están vacíos: no hay bindings propios, observers ni configuración de dominio en el provider.

La raíz web `/` devuelve `welcome`. La interfaz web no implementa el producto; Vite y las vistas adicionales soportan recursos mínimos y la documentación Scribe.

## Responsabilidades por capa

| Capa | Responsabilidad actual | No asumir |
| --- | --- | --- |
| Rutas | URI, verbo, nombres REST implícitos y middleware | Reglas de negocio o forma completa de respuesta |
| Middleware | Throttling de registro/login y autenticación Sanctum del resto | Ownership del recurso |
| Controladores API | Orquestación, consultas, ownership adicional, persistencia, relaciones y mensajes | Una capa de casos de uso separada |
| Form Requests | Validación y mensajes; todos retornan `true` en `authorize()` | Que `exists` valide pertenencia |
| Policies | Autorización REST de vehículos y recursos hijos | Filtrado de listados o nuevo `vehicle_id` |
| Modelos | Asignación masiva, casts y relaciones Eloquent | Reglas encapsuladas o scopes propios |
| Resources | Campos, formato, relaciones condicionales y metadatos | Que toda relación use su Resource específico |
| Migraciones/seeders | Esquema y catálogos iniciales | Contrato HTTP |
| Scribe | Documentación pública generada | Ser el código ejecutado por la API |

El [inventario de componentes](inventories/components.md) enumera las clases concretas.

## Flujos representativos

### Listado protegido

1. Una ruta dentro de `auth:sanctum` autentica el token.
2. `authorizeResource` enlaza `index()` con `Policy::viewAny()` en los cuatro recursos con policy.
3. El controlador limita la consulta al usuario: `user()->vehicles()` para vehículos o `whereIn(vehicle_id, ids del usuario)` para cargas, mantenimientos y recordatorios.
4. La consulta usa `latest()->paginate()`, salvo catálogos.
5. Una `ResourceCollection` produce `data` y, en recursos de negocio paginados, metadatos personalizados.

### Creación o actualización

1. El Form Request valida estructura, tipos y referencias existentes.
2. En `store`, `authorizeResource` invoca `create()`; en `update`, el binding resuelve el modelo y la policy verifica al propietario actual.
3. Vehículos fuerza `user_id` desde la sesión. Los recursos hijos comprueban el `vehicle_id` mediante `user()->vehicles()->findOrFail()`.
4. El controlador crea o actualiza directamente el modelo.
5. El Resource serializa; recordatorios cargan prioridad y vehículo, y vehículos administran su foto en el disco `public`.

`exists:vehicles,id` solo confirma existencia global. La comprobación de pertenencia separada es parte necesaria del flujo actual.

### Consulta o borrado con binding

Laravel resuelve parámetros resource mediante binding implícito. `authorizeResource` relaciona el parámetro con la policy. Solo `VehicleController` implementa `show`; los otros recursos de negocio excluyen esa acción. `destroy` borra y retorna JSON con `message`; vehículos elimina antes la foto existente.

## Convenciones observadas

- Namespace API `App\Http\Controllers\Api`; clases singulares terminadas en `Controller`.
- Recursos REST con `Route::apiResource`; nombres y parámetros derivados por Laravel.
- Binding implícito: `vehicle`, `maintenance`, `reminder` y `gasoline_refill`; este último llega como `$gasolineRefill` en PHP.
- Validadores `Store*`/`Update*`; autenticación usa `LoginRequest` y `StoreRegisterRequest`.
- Una clase `*Resource` por entidad y `*Collection` para listados; borrados usan `JsonResponse` manual.
- `authorizeResource(Model::class, 'route_parameter')` en cuatro controladores; policies descubiertas por convención.
- Persistencia con `validated()` y `create()`/`update()`; sin DTOs, commands ni transacciones explícitas.
- Listados de negocio ordenados por `created_at` descendente y paginación predeterminada, sin `per_page` propio.
- Carga anticipada cuando la respuesta la necesita; Resources usan `whenLoaded()` para relaciones.
- Etiquetas Scribe (`@group`, `@authenticated`, `@bodyParam`, `@urlParam`) en PHPDoc de controladores.

## Excepciones comprobadas

| Tema | Convención principal | Excepción |
| --- | --- | --- |
| Acciones REST | CRUD API | Solo vehículos expone `show` |
| Catálogos | Colección bajo `data` | Tipos pagina; estados y prioridades usan `all()` |
| Paginación | `data` y metadatos propios | Catálogos no paginados solo tienen `data`; metadatos mezclan camelCase y snake_case |
| Relaciones | `whenLoaded()` | `VehicleResource` entrega relaciones directamente; `ReminderResource` envuelve prioridad y no serializa el vehículo cargado |
| Ownership | Policy para modelo enlazado | Listados y pertenencia de un nuevo `vehicle_id` se comprueban en controlador |
| Creación | Resource con estado implícito | Registro construye JSON y fija HTTP 201; otros stores no fijan código |
| Borrado | JSON con `message` | Texto distinto por recurso; no se usa 204 |
| Archivos | Sin archivos normalmente | Vehículos guarda, reemplaza y elimina fotos y genera URL absoluta |

Estas diferencias son contrato observable y no deben normalizarse incidentalmente.

## Archivos que suelen cambiar juntos

| Cambio | Archivos probables |
| --- | --- |
| Campo de entrada/salida | Migración, modelo, Requests store/update, Resource, Scribe y tests |
| Acción REST | Ruta, controlador, policy, Request, Resource/Collection, Scribe y tests |
| Ownership | Controlador, policy, relaciones/consultas y tests de acceso cruzado |
| Listado | Consulta, Collection, Scribe y tests de paginación |
| Relación incluida | Modelo, eager loading, Resource y tests |
| Foto de vehículo | Requests, controlador, Resource, filesystem y tests |
| Catálogo | Migración/modelo, seeder, controlador, Resource/Collection y consumidores |

Los detalles de datos están en [Modelo de dominio](03-domain-model.md); los endpoints están en [Mapa de la API](04-api-map.md).

## Capas ausentes

No se encontraron servicios, repositorios, DTOs, actions/use cases, jobs propios, eventos/listeners, observers, comandos Artisan propios ni middleware de aplicación. Tampoco hay configuración propia en el provider o excepciones. Laravel conserva infraestructura configurada para colas, caché, mail y sesiones; su uso real se documentará después. Esta ausencia es descriptiva, no un defecto automático.

## Verificación y limitaciones

La estructura se contrastó directamente con rutas y clases. `php artisan route:list --path=api` no pudo arrancar porque el runtime no encontró `Laravel\Sanctum\Sanctum`; por ello no se usa una salida de Artisan como evidencia. Antes de depender de nombres o middleware generados, restaura dependencias y vuelve a ejecutar `php artisan route:list`.

Última verificación: 2026-08-21.

Fuentes consultadas: `bootstrap/app.php`, `bootstrap/providers.php`, `routes/api.php`, `routes/web.php`, `app/Http/Controllers/`, `app/Http/Requests/`, `app/Http/Resources/`, `app/Models/`, `app/Policies/`, `app/Providers/AppServiceProvider.php`, `resources/`, `.scribe/` y `config/scribe.php`.
