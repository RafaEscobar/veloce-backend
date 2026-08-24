# Documentación de Veloce para Codex

Este directorio es la entrada técnica para explorar y modificar Veloce. Empieza por la [ficha del proyecto](01-project-overview.md): resume el producto comprobable en el repositorio, sus módulos, vocabulario y fuentes de verdad.

## Orden de lectura

| Orden | Documento | Cuándo consultarlo | Estado |
| --- | --- | --- | --- |
| 1 | [Ficha del proyecto](01-project-overview.md) | Al iniciar cualquier tarea o cuando falte contexto de dominio | Disponible |
| 2 | [Arquitectura](02-architecture.md) | Para localizar capas y archivos que cambian juntos | Disponible |
| 3 | [Modelo de dominio](03-domain-model.md) | Antes de cambiar entidades, relaciones o persistencia | Disponible |
| 4 | [Mapa de la API](04-api-map.md) | Antes de modificar un endpoint o su contrato | Disponible |
| 5 | [Autenticación y seguridad](05-auth-security.md) | Para autenticación, autorización y ownership | Disponible |
| 6 | [Ciclo de vida de datos](06-data-lifecycle.md) | Para migraciones, seeders, factories y borrados | Disponible |
| 7 | [Desarrollo local](07-local-development.md) | Para instalar, ejecutar y configurar el proyecto | Disponible |
| 8 | [Pruebas y verificación](08-testing.md) | Para elegir y ejecutar verificaciones | Disponible |
| 9 | [Operación y observabilidad](09-operations.md) | Para operación, observabilidad y diagnóstico | Disponible |
| 10 | [Playbooks de cambios](10-change-playbooks.md) | Para cambios recurrentes de extremo a extremo | Disponible |
| 11 | [Riesgos conocidos](11-known-risks.md) | Para riesgos confirmados y decisiones pendientes | Disponible; mantenimiento continuo |

Los [inventarios de componentes](inventories/components.md), [base de datos](inventories/database.md) y [rutas](inventories/routes.md) acompañan a la arquitectura, el dominio y la API. El [inventario de configuración](inventories/configuration.md) completa el conjunto actual; los inventarios previstos ya están disponibles bajo `inventories/` en los pasos indicados por el [plan de documentación](../CODEX_DOCUMENTATION_PLAN.md).

## Mapa rápido del código

- `routes/api.php`: superficie HTTP de la API.
- `app/Http/Controllers/Api/`: acciones de autenticación, recursos de negocio y catálogos.
- `app/Http/Requests/`: validación de entrada.
- `app/Policies/`: autorización de recursos.
- `app/Models/`: entidades y relaciones Eloquent.
- `app/Http/Resources/`: representación JSON.
- `database/migrations/` y `database/seeders/`: esquema y valores iniciales de catálogo.
- `.scribe/` y `config/scribe.php`: artefactos y configuración de la documentación pública de la API.
- `tests/`: verificaciones automatizadas; actualmente solo contiene los ejemplos iniciales.

## Fuentes de verdad

No toda la documentación tiene la misma autoridad. Ante una discrepancia, usa este criterio:

| Pregunta | Fuente primaria | Fuentes de contraste |
| --- | --- | --- |
| ¿Qué endpoints se ejecutan y con qué middleware? | `routes/*.php` y la salida de `php artisan route:list` | Controladores y Scribe |
| ¿Cómo se comporta una operación? | Código ejecutable en `app/` | Tests y documentación Scribe |
| ¿Qué datos persisten y qué restricciones tienen? | `database/migrations/` | Modelos, Requests y seeders |
| ¿Qué contrato público está documentado? | Scribe generado desde el código y su configuración | Rutas, Requests y Resources |
| ¿Qué comportamiento está protegido contra regresiones? | Tests que se ejecutan correctamente | Código y documentación |
| ¿Qué versiones se resuelven? | `composer.lock` y `package-lock.json` | Restricciones de `composer.json` y `package.json` |

Scribe es la referencia consumible del contrato de la API, pero no reemplaza las rutas ni el código en ejecución. Un test demuestra solamente el escenario que cubre; la ausencia de un test no define comportamiento.

## Reglas de actualización

- Actualiza el documento afectado en el mismo cambio que modifica comportamiento, esquema, API, configuración u operación.
- Enlaza rutas reales del repositorio y evita copiar explicaciones genéricas de Laravel.
- Separa hechos verificados, inferencias y decisiones pendientes; no presentes intención de producto no respaldada como comportamiento existente.
- No incluyas secretos, credenciales, tokens, datos personales ni valores procedentes de `.env`.
- No dupliques ejemplos extensos de Scribe: enlaza el contrato y documenta aquí su trazabilidad interna.
- Conserva los nombres canónicos del glosario; menciona alias solo cuando sean necesarios para buscar código o hablar con negocio.
- Añade o renueva la fecha de “Última verificación” y las fuentes consultadas cuando compruebes materialmente un documento.
- Comprueba que los enlaces y comandos sigan siendo válidos antes de cerrar el cambio.

## Política de mantenimiento

| Cambio realizado | Documentación y verificaciones que activa |
| --- | --- |
| Ruta, controlador, Request o Resource | `04-api-map.md`, inventario de rutas, Scribe y pruebas Feature del contrato |
| Migración, modelo, factory o seeder | `03-domain-model.md`, `06-data-lifecycle.md`, inventario de base de datos y pruebas de persistencia |
| Middleware, autenticación o policy | `05-auth-security.md`, registro de riesgos, matriz 401/403/ownership y metadatos Scribe |
| Dependencia, configuración o variable de entorno | `07-local-development.md`, inventario de configuración, `.env.example` y README público cuando corresponda |
| Storage, jobs, logs, health o despliegue | `09-operations.md`, registro de riesgos y procedimientos operativos |
| Flujo recurrente de cambio | `10-change-playbooks.md` y los documentos de dominio afectados |

La documentación se mantiene en el mismo pull request que cambia su fuente de verdad. Si una verificación no puede pasar, se registra el comando, el resultado y el riesgo correspondiente; no se presenta como validada por inferencia.

## Checklist documental para pull requests

- Identificar las filas de la política anterior que activa el cambio.
- Comparar rutas y middleware con `php artisan route:list` cuando cambie la API.
- Revisar migración, rollback y datos de prueba cuando cambie el esquema.
- Regenerar Scribe y revisar YAML, HTML, OpenAPI y Postman cuando cambie el contrato.
- Probar 401, 403, ownership cruzado, 422 y éxito cuando cambie seguridad o validación.
- Mantener `.env.example` sin secretos y explicar cualquier configuración nueva.
- Revisar health, workers, almacenamiento y logs cuando cambie la operación.
- Ejecutar las verificaciones proporcionales al cambio y registrar cualquier fallo conocido.
- Renovar “Última verificación”, fuentes y riesgos de los documentos tocados.

## Línea base de validación del 2026-08-24

| Comando o revisión | Resultado |
| --- | --- |
| `composer install --no-interaction --prefer-dist` | Correcto; dependencias PHP instaladas |
| `php artisan route:list --path=api --json` | Correcto; devuelve 24 filas: 23 bajo `api/` (21 protegidas y 2 públicas) y `docs.openapi` |
| `composer test` | No pasa: 1 prueba Unit correcta y 1 Feature falla por `MissingAppKeyException` |
| `./vendor/bin/pint --test` | No pasa: 27 archivos PHP requieren formato |
| `npm run build` | Correcto; build de Vite generado |
| `php artisan scribe:generate` | Correcto; 23 acciones, con respuestas aún vacías y advertencias de `bodyParameters()` |
| Enlaces Markdown locales y `git diff --check` | Correcto tras limpiar whitespace de la vista generada |

## Estado de esta base documental

Esta base cubre los 10 pasos del plan. El paso 10 queda activo como política de mantenimiento continuo para futuros cambios.

Última verificación: 2026-08-24.

Fuentes consultadas: `docs/CODEX_DOCUMENTATION_PLAN.md`, `bootstrap/`, `routes/`, `app/`, `composer.json`, `composer.lock`, `package.json`, `phpunit.xml`, `database/`, `config/scribe.php`, `resources/`, `.scribe/`, artefactos generados de Scribe y `tests/`.
