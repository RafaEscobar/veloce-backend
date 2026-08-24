# Operación y observabilidad

## Estado operativo observable

Veloce no incluye infraestructura de despliegue o CI en el repositorio. La operación confirmada se limita al runtime Laravel, MySQL configurado, tokens Sanctum, filesystem público para fotos, logs estándar y artefactos de documentación. No hay evidencia local para afirmar plataforma, topología, proxy final, proceso manager, backups o estrategia de release.

## Componentes configurados y usados

| Componente | Configuración local | Uso de negocio confirmado |
| --- | --- | --- |
| HTTP/API | Laravel; `/api`, `/`, `/up`, `/docs` generado | Sí |
| Base de datos | MySQL en `.env.example` | Sí: dominio, tokens y catálogos |
| Filesystem | Default `local`; fotos fuerzan `public` | Sí: fotos de vehículos |
| Logs | `stack` → `single`, nivel debug | Framework; no hay llamadas de log propias |
| Colas | Database; listener en `composer dev` | No hay Jobs ni `dispatch()` propios |
| Caché | Database | Sin uso explícito de negocio |
| Sesiones | Database | Configurada; flujos API actuales usan bearer tokens |
| Mail | Log | Sin Mailables ni envíos propios |
| Broadcast | Log | Sin eventos/broadcast propios |
| Redis/Memcached/S3 | Conexiones disponibles | Sin uso con defaults actuales |
| Scheduler | Infraestructura Laravel disponible | Sin tareas programadas |

“Configurado” no significa “necesario en producción”. Antes de desplegar, decide qué drivers se mantienen y elimina procesos innecesarios solo con evidencia del entorno objetivo.

## Logs

`config/logging.php` define canales stack, single, daily, Slack, Papertrail, stderr, syslog, errorlog, null y emergency. `.env.example` selecciona:

```text
LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=debug
```

El destino efectivo local es `storage/logs/laravel.log`. `composer dev` ejecuta Pail para seguir eventos/logs en vivo. No existen mensajes estructurados propios, IDs de correlación, auditoría de login, métricas ni trazas distribuidas.

No copies logs completos a issues o documentación: pueden contener headers, rutas locales, SQL, emails o stack traces. Redacta tokens y datos personales.

## Excepciones y errores HTTP

`bootstrap/app.php` deja vacío `withExceptions`; se usa el manejo predeterminado de Laravel. No existe formato de error propio ni reporter adicional.

Para clientes JSON se esperan respuestas estándar:

- 401 por `auth:sanctum`;
- 403 por policies;
- 404 por binding o `findOrFail`;
- 422 por Form Requests/ValidationException;
- 429 por throttling;
- 500 para excepciones no manejadas.

Con `APP_DEBUG=true`, el entorno local puede mostrar trazas detalladas. En cualquier entorno expuesto debe ser `false`. Las pruebas actuales no congelan la forma exacta de error.

## Health check

`bootstrap/app.php` registra `/up` mediante el health route estándar.

```bash
curl -fsS http://127.0.0.1:8000/up
```

Un 200 confirma que Laravel pudo arrancar y responder. No confirma conectividad MySQL, migraciones, escritura en Storage, worker de cola, mail, Scribe ni servicios externos. Un monitor de disponibilidad debe tratarlo como liveness básica, no readiness completa.

## Colas y procesos

`QUEUE_CONNECTION=database` y existen tablas `jobs`, `job_batches` y `failed_jobs`. `composer dev` inicia:

```bash
php artisan queue:listen --tries=1 --timeout=0
```

No hay Jobs, listeners queued ni llamadas a `dispatch()` en código local. El worker es parte del script estándar, pero actualmente no ejecuta trabajo de dominio.

Comandos de diagnóstico no destructivos cuando las dependencias estén completas:

```bash
php artisan queue:failed
php artisan about
php artisan config:show queue
```

No uses `queue:retry`, `queue:flush` o borrado directo de tablas durante diagnóstico sin resolver antes alcance e impacto.

## Caché, sesiones, mail y broadcast

Las tablas/drivers están disponibles, pero no se encontraron llamadas propias a Cache, sesión, Mail, Notification o Broadcast. Para distinguir configuración de uso:

```bash
php artisan config:show cache
php artisan config:show session
php artisan config:show mail
```

Mail usa `log`, por lo que un envío futuro no saldría del sistema local. Cambiar driver exige credenciales, gestión de secretos y una prueba de entrega controlada.

## Storage y fotos

Fotos se guardan en `storage/app/public/vehicles` y se sirven a través de `public/storage`. Diagnóstico:

```bash
ls -ld storage/app/public public/storage
php artisan config:show filesystems
```

Si falta el enlace, créalo con `php artisan storage:link`. El acceso público y los archivos huérfanos están registrados como `SEC-001` y `DATA-001` en `11-known-risks.md`.

Los discos tienen `throw=false` y `report=false`; al investigar cargas fallidas comprueba permisos, espacio disponible, enlace y valor efectivo de `APP_URL`.

## Scribe

`config/scribe.php` usa salida Laravel y registra:

- `/docs` para HTML;
- `/docs.postman` para la colección;
- `/docs.openapi` para OpenAPI;
- assets en `public/vendor/scribe`;
- YAML persistido en `.scribe/endpoints`;
- Postman/OpenAPI en `storage/app/private/scribe`.

Generación:

```bash
php artisan scribe:generate
```

La generación del 2026-08-24 produjo ocho grupos YAML más la plantilla custom, 23 acciones, assets, Postman y OpenAPI. Autenticación quedó en 21 protegidas y 2 públicas. Las respuestas siguen vacías por diseño hasta añadir ejemplos o tags explícitos; revisa YAML, Blade y specs en cada regeneración.

## Diagrama ER

Existe `graph.png` en la raíz (1788×2306 al verificar). Se regenera con:

```bash
php artisan generate:erd
```

Requiere Graphviz (`dot -V`). El resultado deriva de relaciones Eloquent y puede diferir del esquema; contrástalo con migraciones e `inventories/database.md`.

## Scheduling y comandos

`routes/console.php` solo registra `inspire`, comando de ejemplo de Laravel. No hay comandos de aplicación ni tareas programadas. Por tanto, el repositorio no justifica actualmente un cron de `schedule:run`; si se añade scheduling, documenta expresión, timezone, exclusión mutua, reintentos y observabilidad.

## Diagnóstico por síntoma

| Síntoma | Comprobaciones no destructivas |
| --- | --- |
| Artisan no arranca | `composer install`; `composer check-platform-reqs`; confirmar `vendor/laravel/sanctum`; revisar error sin publicar secretos |
| `/up` falla | Proceso HTTP, `APP_KEY`, permisos de bootstrap/storage y últimas líneas redactadas del log |
| API devuelve 401 | Ruta protegida, header Bearer, existencia/revocación del token; nunca imprimir token completo |
| API devuelve 403 | Policy y owner actual; comparar `user_id`/`vehicle.user_id` mediante una consulta controlada |
| Validación 422 inesperada | Request de store/update, Content-Type y discrepancias con migración |
| Error de base | `php artisan migrate:status`; `php artisan db:show`; conexión/config efectiva; no ejecutar fresh |
| Foto no carga | `public/storage`, permisos, archivo bajo `storage/app/public`, `APP_URL` y URL del Resource |
| Cola no procesa | Confirmar que realmente exista un job; `queue:failed`; conexión/tablas y proceso listener |
| Docs incompletas | `route:list`, `config/scribe.php`, anotaciones, YAML y diff después de `scribe:generate` |
| ERD falla | `dot -V`, paquete dev instalado y relaciones de modelos |
| Build falla | `npm ci`, versión Node, `npm run build` y entradas de `vite.config.js` |

`route:list`, `migrate:status`, `db:show`, `about` y `config:show` requieren dependencias completas. Sanctum quedó instalado y Artisan arrancó correctamente en la verificación del 2026-08-24.

## Despliegue: solo hechos confirmados

No se encontraron Dockerfiles, compose, workflows CI, Procfile, unidades systemd, configuración Supervisor ni scripts de deploy. Tampoco hay política documentada de backups, rollback, secrets, TLS o workers. Estas decisiones pertenecen a la infraestructura real y no deben inventarse desde los defaults locales.

Antes de desplegar deben confirmarse al menos: servidor PHP, web server/proxy, `APP_DEBUG=false`, secrets, migraciones, storage persistente/enlace, permisos, logs/rotación, proceso de cola si se usa, health/readiness, backups y regeneración/publicación de Scribe.

## Límites de observabilidad confirmados

- `/up` es liveness superficial.
- No hay logging ni métricas de dominio.
- El canal local `single` no rota por sí mismo.
- No hay alertas, tracing, dashboards o reporter externo configurado por el proyecto.
- No hay pruebas de operación ni CI que ejecuten comandos de verificación.

Consulta [Riesgos conocidos](11-known-risks.md) para acciones y estado.

Última verificación: 2026-08-24.

Fuentes consultadas: `bootstrap/app.php`, `routes/console.php`, `composer.json`, `.env.example`, configuración de logs/colas/cache/session/mail/filesystem/Scribe, búsquedas de uso en `app/`, artefactos Scribe y `graph.png`.
