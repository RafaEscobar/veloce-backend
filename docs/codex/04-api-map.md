# Mapa trazable de la API

## Alcance y fuente de verdad

La superficie ejecutable está declarada en `routes/api.php` y se sirve bajo `/api`. Este mapa conecta esa superficie con las capas internas; Scribe sigue siendo el contrato consumible, pero su cobertura actual tiene diferencias documentadas abajo. El inventario fila por fila está en [inventories/routes.md](inventories/routes.md).

Hay 23 acciones lógicas y 27 combinaciones método/URI porque cada una de las cuatro actualizaciones acepta `PUT` y `PATCH`.

## Flujo común

- Registro y login son públicos y tienen throttles explícitos: 3/min y 5/min, respectivamente.
- Las otras 21 acciones están dentro de `auth:sanctum`.
- Vehículos, cargas, mantenimientos y recordatorios enlazan acciones REST con policies mediante `authorizeResource`.
- Los Form Requests validan datos; `authorize()` retorna `true` en todos ellos.
- Controllers consultan Eloquent directamente y Resources/Collections definen la salida.
- Las rutas con modelo usan binding implícito; un ID inexistente produce el comportamiento 404 predeterminado.

## Autenticación

| Acción | Trazabilidad | Respuesta de éxito observada en código |
| --- | --- | --- |
| Registro | `AuthController::register` → `StoreRegisterRequest` → `User` → `UserResource` | 201; objeto `{user, token}` |
| Login | `AuthController::login` → `LoginRequest` → Auth/`User` → `UserResource` | 200 predeterminado; `{user, token}` |
| Logout | `AuthController::logout` → usuario/token Sanctum | 200; `{message}`; elimina todos los tokens del usuario |

Registro/login no usan policy. Credenciales incorrectas se expresan como error de validación; las reglas precisas están en [Autenticación y seguridad](05-auth-security.md).

## Vehículos

`VehicleController` usa `VehiclePolicy`, Requests separados para store/update y `VehicleResource`/`VehicleCollection`.

- `index`: parte de `request.user.vehicles`, carga tipo/estado, ordena por creación descendente y pagina.
- `store`: fuerza `user_id`, guarda foto opcional y crea el vehículo.
- `show`: policy sobre el binding; carga tipo, estado, cargas, mantenimientos y recordatorios.
- `update`: policy sobre propietario actual; puede reemplazar foto.
- `destroy`: policy, elimina foto y fila; las FKs eliminan hijos.

No hay filtros, búsqueda ni orden configurables. `page` funciona por el paginator estándar; no existe `per_page` aceptado por el controlador.

## Cargas, mantenimientos y recordatorios

Los tres recursos siguen el mismo patrón: `index`, `store`, `update`, `destroy`, sin `show`.

| Recurso | Policy | Store/Update Request | Resource/Collection | Particularidades |
| --- | --- | --- | --- | --- |
| Carga | `GasolineRefillPolicy` | `StoreGasolineRefillRequest` / `UpdateGasolineRefillRequest` | `GasolineRefillResource/Collection` | Update Request no acepta cambiar vehículo |
| Mantenimiento | `MaintenancePolicy` | `StoreMaintenanceRequest` / `UpdateMaintenanceRequest` | `MaintenanceResource/Collection` | Update permite mover a otro vehículo propio |
| Recordatorio | `ReminderPolicy` | `StoreReminderRequest` / `UpdateReminderRequest` | `ReminderResource/Collection` | Carga prioridad; Resource devuelve fecha sin hora |

Los listados filtran por IDs de vehículos del usuario, usan `latest()` y `paginate()`. Store valida que el vehículo pertenezca al usuario con `findOrFail`; update repite esa comprobación si el Request admite un nuevo `vehicle_id`.

No hay filtros por vehículo, fecha, estado, prioridad ni texto. Tampoco orden o tamaño de página configurables.

## Catálogos

Los tres endpoints son GET autenticados, sin Form Request ni policy propia.

- Tipos: `VehicleType::paginate()` → `VehicleTypeCollection`.
- Estados: `VehicleStatus::all()` → `VehicleStatusCollection`.
- Prioridades: `ReminderPriority::all()` → `ReminderPriorityCollection`.

Solo tipos acepta de hecho el query estándar `page`; las otras dos respuestas no se paginan.

## Forma de respuestas

| Clase de acción | Forma |
| --- | --- |
| Listado de negocio | `data` y `firstPage`, `lastPage`, `total`, `currentPage`, `per_page` |
| Tipos de vehículo | Solo `data` definido por la Collection; `paginationInformation()` suprime metadatos adicionales |
| Estados/prioridades | Solo `data` definido por la Collection |
| Entidad creada/actualizada/detalle | Envoltura estándar de `JsonResource` alrededor de los campos del Resource |
| Registro/login | Objeto manual con `user` y `token` |
| Borrado/logout | Objeto manual con `message` |

Los stores de recursos retornan `JsonResource` sin fijar status; por defecto se espera 200 en este código. Solo registro fija 201. Los borrados retornan 200, no 204.

## Errores esperados por código/framework

| Condición | Estado esperado | Origen |
| --- | --- | --- |
| Sin token o token inválido en ruta protegida | 401 | `auth:sanctum` |
| Policy rechaza recurso de otro usuario | 403 | `authorizeResource`/policy |
| Binding no encuentra ID | 404 | route model binding |
| `vehicle_id` existe pero no pertenece al usuario en store/update | 404 | relación del usuario + `findOrFail` |
| Datos inválidos o credenciales incorrectas | 422 | Form Request/`ValidationException` |
| Throttle superado | 429 | middleware `throttle` |
| Restricción DB incompatible con Request | 500 posible | discrepancias descritas en `03-domain-model.md` |

Son resultados derivados del código y defaults de Laravel. No hay manejador local de excepciones ni tests funcionales que congelen el formato exacto de error.

## Comparación con Scribe

La generación del 2026-08-24 cubre las 23 acciones cuyo URI comienza con `api/` en la salida de `php artisan route:list --path=api`: 21 protegidas y las dos públicas, register y login. La configuración global de Scribe declara autenticación por bearer token y esas dos acciones usan `@unauthenticated`.

| Estado | Resultado |
| --- | --- |
| Cobertura | 23 de 23 acciones lógicas presentes en ocho grupos numerados de `.scribe/endpoints/` |
| Autenticación | 21 entradas con `authenticated: true` y 2 con `false`, consistente con las rutas |
| Respuestas | Las entradas conservan `responses: []` y `responseFields: []`; faltan ejemplos y esquemas explícitos |
| Parámetros | Scribe obtiene las reglas básicas de los Form Requests, pero avisa que no implementan `bodyParameters()` |
| Artefactos | YAML y vista versionados; OpenAPI y Postman se generan bajo `storage/app/private/scribe/` |

Las llamadas automáticas de respuesta están deshabilitadas para evitar dependencia de datos locales y efectos secundarios durante la generación. Cada cambio de contrato debe aportar anotaciones o estrategias seguras que documenten respuestas reales y revisar el diff generado.

## Checklist para cambiar un endpoint

1. Localiza la fila en `inventories/routes.md`.
2. Revisa middleware, binding y policy antes de tocar el controlador.
3. Contrasta Request con migración y ownership.
4. Conserva forma, relaciones y status del Resource/Collection.
5. Actualiza anotaciones Scribe y regenera sus artefactos.
6. Añade pruebas de éxito, validación, 401, 403/ownership y 404 según aplique.

Última verificación: 2026-08-24.

Fuentes consultadas: `routes/api.php`, controladores API, Requests, Resources/Collections, modelos, policies, `bootstrap/app.php`, `config/scribe.php` y `.scribe/endpoints/*.yaml`.
