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

Registro/login no usan policy. Credenciales incorrectas se expresan como error de validación; las reglas precisas se ampliarán en el paso 5.

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

Los YAML de `.scribe/endpoints/` contienen 15 acciones: autenticación y store/update/destroy de los cuatro recursos. Comparados con las 23 acciones de rutas:

| Estado | Acciones |
| --- | --- |
| Cubiertas | register, login, logout; store/update/destroy de vehículos, cargas, mantenimientos y recordatorios |
| Ausentes | index de vehículos, cargas, mantenimientos y recordatorios; show de vehículo; índices de tipos, estados y prioridades |
| Autenticación incorrecta | store/update/destroy de vehículos aparecen `authenticated: false`, aunque están bajo `auth:sanctum` |
| Respuestas incompletas | Las 15 entradas tienen `responses: []` y `responseFields: []` |
| Trazabilidad generada incompleta | Entradas guardadas muestran `controller`, `method` y `route` como `null` |
| Configuración incompatible | `config/scribe.php` tiene `auth.enabled: false` aunque 21 acciones requieren Sanctum |

Los recursos hijos presentes sí aparecen como autenticados; logout también. Los ejemplos de body reflejan anotaciones/Requests, incluidas las nulabilidades de vehículo que contradicen la migración.

No se regeneró Scribe en este paso: primero debe restaurarse la instalación de dependencias, pues Artisan no encuentra actualmente `Laravel\\Sanctum\\Sanctum`. Regenerar puede sobrescribir YAML, HTML, Postman y OpenAPI; debe hacerse junto con la corrección de anotaciones/configuración y una revisión del diff.

## Checklist para cambiar un endpoint

1. Localiza la fila en `inventories/routes.md`.
2. Revisa middleware, binding y policy antes de tocar el controlador.
3. Contrasta Request con migración y ownership.
4. Conserva forma, relaciones y status del Resource/Collection.
5. Actualiza anotaciones Scribe y regenera sus artefactos.
6. Añade pruebas de éxito, validación, 401, 403/ownership y 404 según aplique.

Última verificación: 2026-08-21.

Fuentes consultadas: `routes/api.php`, controladores API, Requests, Resources/Collections, modelos, policies, `bootstrap/app.php`, `config/scribe.php` y `.scribe/endpoints/*.yaml`.
