# Pruebas y verificación

## Estado actual

La suite usa Pest 3 sobre PHPUnit y separa `tests/Feature` y `tests/Unit`. `phpunit.xml` configura:

- SQLite en memoria;
- bcrypt con 4 rounds;
- cache/session array, cola sync, mail array y broadcast null;
- código fuente de cobertura bajo `app/`.

Solo existen:

- `Feature/ExampleTest.php`: comprueba 200 en `/`;
- `Unit/ExampleTest.php`: comprueba que `true` sea verdadero.

No hay pruebas de API, dominio, autenticación, policies, Resources, migraciones ni Scribe. `RefreshDatabase` está comentado en `tests/Pest.php`, por lo que cada archivo Feature nuevo debe activarlo o habilitarlo globalmente.

Al 2026-08-24, las dependencias están completas y `composer test` ejecuta la suite: Unit pasa y Feature falla con `MissingAppKeyException` porque `phpunit.xml` no define `APP_KEY` y el `.env` disponible no aporta una clave. La suite aún no está verde.

## Estrategia

Prioridad: pruebas Feature que atraviesen ruta, middleware, validación, policy, Eloquent y Resource. Son la protección adecuada para el contrato y ownership actuales.

Usa pruebas Unit solo para reglas PHP aisladas sin HTTP ni base de datos. Hoy no hay servicios o reglas puras que justifiquen una matriz Unit propia; policies simples, casts y Resources aportan más valor probados mediante Feature.

Cada archivo Feature de dominio debe usar `RefreshDatabase` y crear explícitamente sus catálogos/relaciones. Evita depender del orden de tests.

## Matriz Feature — autenticación

| Acción | Casos mínimos |
| --- | --- |
| Register | 201 y `{user,token}`; usuario/token persistidos; password no expuesto; campos requeridos/email/min 8 → 422; email repetido → 422; throttle → 429 |
| Login | 200 y nuevo token; credenciales correctas/incorrectas; forma inválida → 422; password no expuesto; throttle → 429 |
| Logout | 200 autenticado; 401 sin token; todos los tokens del usuario revocados; tokens de otro usuario intactos |

## Matriz Feature — vehículos

| Acción | Éxito | Seguridad/errores | Contrato y efectos |
| --- | --- | --- | --- |
| Index | Lista paginada | 401; solo vehículos propios | Orden descendente; tipo/estado; metadatos exactos |
| Store | Crea con owner forzado | 401; validación 422; ignora/rechaza `user_id` externo | Status actual 200; foto con `Storage::fake`; campos DB vs nulabilidad |
| Show | Devuelve vehículo propio | 401; ajeno 403; inexistente 404 | Relaciones cargadas y campos exactos, incluidos hijos directos |
| Update | Cambia campos propios | 401; ajeno 403; validación 422 | Reemplazo/eliminación de foto; status 200 |
| Destroy | Borra propio | 401; ajeno 403; inexistente 404 | Mensaje 200; foto eliminada; hijos en cascada |

Las pruebas de store/update deben caracterizar la discrepancia actual: Requests permiten null/omisión en campos NOT NULL. Decide primero si el test congela el fallo actual o acompaña una corrección; no escribas una expectativa ideal que el código no implementa.

## Matriz Feature — recursos hijos

Aplicar a cargas, mantenimientos y recordatorios:

| Acción | Casos comunes |
| --- | --- |
| Index | 200; 401; solo filas de vehículos propios; orden; paginación y forma `data`/metadatos |
| Store | Éxito en vehículo propio; 401; validación 422; vehículo inexistente 422; vehículo ajeno existente 404; fila/Resource correctos |
| Update | Éxito propio; 401; recurso ajeno 403; inexistente 404; validación 422 |
| Destroy | Éxito y mensaje; 401; ajeno 403; inexistente 404; fila eliminada |

Casos específicos:

- Carga: decimales, nullables, fecha `Y-m-d`, no aceptar cambio de `vehicle_id`.
- Mantenimiento: costo, boolean default/cast, campos nullables; mover a otro vehículo propio y rechazar uno ajeno.
- Recordatorio: prioridad válida, descripción, mover a vehículo propio, prioridad cargada y pérdida actual de hora en Resource.

No hay rutas `show` para estos recursos; la ausencia ya se verifica en el inventario de rutas, no requiere tres pruebas 404 salvo que se quiera congelar explícitamente esa superficie.

## Matriz Feature — catálogos

| Endpoint | Casos mínimos |
| --- | --- |
| Vehicle types | 200 autenticado; 401; `data`; paginación; valores seed canónicos |
| Vehicle statuses | 200 autenticado; 401; solo `data`; conjunto seed canónico |
| Reminder priorities | 200 autenticado; 401; solo `data`; conjunto seed canónico |

Añade una prueba de idempotencia de `DatabaseSeeder` y otra que detecte duplicados/capitalización no canónica si se corrigen constraints o factories.

## Matriz transversal de seguridad

Para cada recurso con ownership, prepara dos usuarios, al menos un vehículo por usuario y datos hijos. Comprueba:

1. listado no filtra filas ajenas;
2. binding ajeno produce 403;
3. FK de vehículo ajeno en store/update produce 404 actualmente;
4. ID inexistente conserva 422 o 404 según el punto del flujo;
5. `user_id` nunca se acepta para reasignar vehículo;
6. una foto pública se comporta según la decisión pendiente de `SEC-001`.

Estas pruebas deben impedir retirar accidentalmente scopes, `authorizeResource` o `findOrFail`.

## Factories y fixtures necesarios

| Factory | Trabajo requerido |
| --- | --- |
| `UserFactory` | Añadir `last_name`; eliminar/respaldar `email_verified_at` según esquema |
| `VehicleTypeFactory` | Alinear `Vehículo` y estados deterministas con seeder |
| `VehicleStatusFactory` | Alinear capitalización con seeder |
| `ReminderPriorityFactory` | Crear con estados `critical`, `high`, `medium`, `low`, `optional` o equivalentes canónicos |
| `VehicleFactory` | Asociar user/type/status y llenar todos los NOT NULL; estados `withPhoto` opcionales |
| `GasolineRefillFactory` | Asociar vehículo y valores decimal/fecha válidos |
| `MaintenanceFactory` | Asociar vehículo; estados con/sin costo, notas y reminder flag |
| `ReminderFactory` | Asociar vehículo/prioridad; fecha con hora y descripción opcional |

Para aislamiento, usa factories con estados semánticos y valores explícitos en assertions. Usa `DatabaseSeeder` cuando el comportamiento dependa del conjunto oficial de catálogos, no como sustituto universal de fixtures.

## Organización sugerida

```text
tests/Feature/Api/
├── AuthTest.php
├── VehicleTest.php
├── GasolineRefillTest.php
├── MaintenanceTest.php
├── ReminderTest.php
└── CatalogTest.php
```

Helpers repetidos de usuario autenticado, headers y árbol de ownership pueden vivir en `tests/Pest.php` solo cuando su interfaz sea estable. Evita un helper genérico que oculte qué usuario posee cada fila.

## Comandos

```bash
# Suite completa definida por Composer
composer test

# Suite completa directamente
php artisan test

# Archivo o directorio
php artisan test tests/Feature/Api/VehicleTest.php
php artisan test tests/Feature/Api

# Nombre de test
php artisan test --filter='usuario no puede actualizar vehículo ajeno'

# Salida compacta de Pest
./vendor/bin/pest --compact

# Formato PHP sin modificar archivos
./vendor/bin/pint --test

# Formatear PHP deliberadamente
./vendor/bin/pint
```

Coverage puede ejecutarse con `php artisan test --coverage` solo si el entorno tiene Xdebug/PCOV configurado. No se encontró PHPStan, Psalm ni otro análisis estático configurado; no documentes un comando de análisis como obligatorio hasta incorporarlo al repositorio.

## Verificación proporcional al cambio

| Cambio | Verificación mínima |
| --- | --- |
| Solo documentación | Enlaces, rutas/nombres citados, `git diff --check` |
| Controlador/Request/Resource | Test Feature del recurso + Pint test |
| Policy/ownership/auth | Matriz cruzada 401/403/404/éxito + suite completa |
| Migración/modelo | `migrate:fresh --seed` solo en DB de test desechable, Feature afectadas y revisión de rollback |
| Seeder/factory | Test de idempotencia/factory y Feature consumidoras |
| Foto/filesystem | `Storage::fake('public')`, reemplazo, borrado y acceso esperado |
| Ruta/API | `route:list`, Feature, regenerar Scribe y revisar YAML/OpenAPI/Postman |
| Frontend/vistas Scribe | `npm run build` y revisión de `/docs` |
| Dependencias/config | Suite completa, Pint, build y comando afectado en entorno limpio |

Nunca ejecutes `migrate:fresh` contra una base con datos que deban conservarse.

## Checklist de cierre

- Dependencias completas y Artisan arranca.
- Factory/fixture reproduce el caso sin depender de datos previos.
- Éxito y validación están cubiertos.
- 401 y ownership cruzado están cubiertos cuando aplica.
- Forma JSON, status y efectos en DB/Storage están afirmados.
- `composer test` y `./vendor/bin/pint --test` pasan.
- `npm run build` pasa si cambian assets/vistas.
- Scribe se regenera y revisa si cambia contrato.
- Diferencias SQLite/MySQL se verifican en MySQL cuando afectan tipos, constraints, collation o SQL específico.

Última verificación: 2026-08-24.

Fuentes consultadas: `phpunit.xml`, `tests/`, `database/factories/`, migraciones, rutas, controladores, Requests, Resources, policies, `composer.json` y `package.json`.
