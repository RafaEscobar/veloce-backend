# Inventario de configuración

Fuente principal para configuración local: `.env.example`. Los valores indicados son ejemplos seguros de desarrollo, no valores para producción.

## Aplicación

| Variable | Ejemplo | Propósito / obligatoriedad |
| --- | --- | --- |
| `APP_NAME` | `Laravel` | Nombre visible y prefijos; debería ajustarse a `Veloce` localmente |
| `APP_ENV` | `local` | Entorno; requerido conceptualmente |
| `APP_KEY` | vacío | Clave de cifrado; obligatoria y generada con `key:generate` |
| `APP_DEBUG` | `true` | Diagnóstico detallado; solo seguro localmente |
| `APP_URL` | `http://localhost` | URLs generadas, fotos y Scribe; ajustar al servidor real |
| `APP_LOCALE` | `en` | Locale efectivo; sobrescribe el default `es` de `config/app.php` |
| `APP_FALLBACK_LOCALE` | `en` | Fallback de traducción |
| `APP_FAKER_LOCALE` | `en_US` | Datos Faker |
| `APP_MAINTENANCE_DRIVER` | `file` | Estado de mantenimiento local |
| `APP_MAINTENANCE_STORE` | comentada, `database` | Store si se usa driver cache |
| `PHP_CLI_SERVER_WORKERS` | comentada, `4` | Workers del servidor CLI cuando se habilita |
| `BCRYPT_ROUNDS` | `12` | Costo de hashing de contraseñas |

La aplicación fija timezone UTC en `config/app.php`; no usa variable de entorno para ello.

## Logs

| Variable | Ejemplo | Propósito |
| --- | --- | --- |
| `LOG_CHANNEL` | `stack` | Canal predeterminado |
| `LOG_STACK` | `single` | Canales incluidos en stack |
| `LOG_DEPRECATIONS_CHANNEL` | `null` | Destino de deprecaciones |
| `LOG_LEVEL` | `debug` | Umbral; reducir verbosidad fuera de local |

Con esos valores, el archivo principal es `storage/logs/laravel.log`.

## Base de datos

| Variable | Ejemplo | Propósito |
| --- | --- | --- |
| `DB_CONNECTION` | `mysql` | Driver efectivo |
| `DB_HOST` | `127.0.0.1` | Host |
| `DB_PORT` | `3306` | Puerto |
| `DB_DATABASE` | `veloce` | Base que debe existir antes de migrar |
| `DB_USERNAME` | `root` | Usuario local |
| `DB_PASSWORD` | vacío | Contraseña local; no versionar valores reales |

`config/database.php` también soporta SQLite, MariaDB, PostgreSQL, SQL Server y Redis mediante variables adicionales no incluidas en el ejemplo. Las migraciones son la prueba de compatibilidad que debe ejecutarse al cambiar driver.

## Sesiones, caché y colas

| Variable | Ejemplo | Efecto |
| --- | --- | --- |
| `SESSION_DRIVER` | `database` | Usa tabla `sessions` |
| `SESSION_LIFETIME` | `120` | Minutos de inactividad |
| `SESSION_ENCRYPT` | `false` | Payload de sesión no cifrado por Laravel |
| `SESSION_PATH` | `/` | Path de cookie |
| `SESSION_DOMAIN` | `null` | Dominio de cookie actual |
| `CACHE_STORE` | `database` | Usa tablas `cache`/`cache_locks` |
| `CACHE_PREFIX` | comentada | Evita colisiones entre aplicaciones |
| `QUEUE_CONNECTION` | `database` | Usa `jobs`, batches y failed jobs |
| `BROADCAST_CONNECTION` | `log` | Broadcast al log |

Las tablas correspondientes se crean con las migraciones estándar. No se encontraron llamadas de negocio a caché, sesiones, colas o broadcast; configuración disponible no equivale a uso.

## Redis y Memcached

| Variable | Ejemplo | Uso |
| --- | --- | --- |
| `MEMCACHED_HOST` | `127.0.0.1` | Solo si se selecciona store Memcached |
| `REDIS_CLIENT` | `phpredis` | Cliente Redis |
| `REDIS_HOST` | `127.0.0.1` | Host Redis |
| `REDIS_PASSWORD` | `null` | Credencial opcional |
| `REDIS_PORT` | `6379` | Puerto Redis |

No son necesarios con los defaults database actuales, salvo que otra configuración cambie los drivers.

## Mail

| Variable | Ejemplo | Uso |
| --- | --- | --- |
| `MAIL_MAILER` | `log` | No entrega correo externo |
| `MAIL_SCHEME` | `null` | Esquema SMTP opcional |
| `MAIL_HOST` | `127.0.0.1` | Host SMTP si se selecciona |
| `MAIL_PORT` | `2525` | Puerto SMTP |
| `MAIL_USERNAME` / `MAIL_PASSWORD` | `null` | Credenciales; nunca documentar valores reales |
| `MAIL_FROM_ADDRESS` | `hello@example.com` | Remitente predeterminado |
| `MAIL_FROM_NAME` | `${APP_NAME}` | Nombre del remitente |

No se encontraron Mailables ni envíos de negocio.

## Filesystem y AWS

| Variable | Ejemplo | Uso |
| --- | --- | --- |
| `FILESYSTEM_DISK` | `local` | Disco default privado en `storage/app/private` |
| `AWS_ACCESS_KEY_ID` / `AWS_SECRET_ACCESS_KEY` | vacíos | Credenciales S3 opcionales |
| `AWS_DEFAULT_REGION` | `us-east-1` | Región S3/SQS/DynamoDB |
| `AWS_BUCKET` | vacío | Bucket S3 |
| `AWS_USE_PATH_STYLE_ENDPOINT` | `false` | Compatibilidad S3 |

Fotos de vehículos ignoran el default y usan explícitamente `public`, con raíz `storage/app/public` y URL `${APP_URL}/storage`. Requieren `php artisan storage:link`.

## Frontend

| Variable | Ejemplo | Uso |
| --- | --- | --- |
| `VITE_APP_NAME` | `${APP_NAME}` | Nombre expuesto al bundle Vite |

Solo variables prefijadas con `VITE_` deben considerarse visibles en cliente; nunca pongas secretos en ellas.

## Variables relevantes ausentes de `.env.example`

| Variable consumida | Default/configuración | Cuándo añadirla |
| --- | --- | --- |
| `SANCTUM_STATEFUL_DOMAINS` | localhost y URL actual | Si se implementa autenticación SPA stateful |
| `SANCTUM_TOKEN_PREFIX` | vacío | Para identificar tokens mediante secret scanning |
| `SCRIBE_AUTH_KEY` | vacío/random para response calls | Token dedicado local; nunca versionarlo |
| `APP_PREVIOUS_KEYS` | vacío | Rotación controlada de `APP_KEY` |
| `SESSION_SECURE_COOKIE` | null | Debe activarse con HTTPS si se usan cookies |
| `SESSION_SAME_SITE` | `lax` | Ajuste de cookies cross-site |
| `DB_URL` | ausente | Alternativa compacta a variables DB |
| `AWS_URL` / `AWS_ENDPOINT` | ausentes | S3/CDN o endpoint compatible |

Hay muchas variables opcionales adicionales en `config/*.php`. Añade al ejemplo solo las que el proyecto decida soportar; no copies un `.env` real.

## Archivos y fuentes de verdad

| Área | Archivo principal |
| --- | --- |
| App/locales/clave | `config/app.php` |
| Auth y Sanctum | `config/auth.php`, `config/sanctum.php` |
| DB y Redis | `config/database.php` |
| Filesystem | `config/filesystems.php` |
| Logs | `config/logging.php` |
| Mail | `config/mail.php` |
| Caché | `config/cache.php` |
| Sesiones | `config/session.php` |
| Colas | `config/queue.php` |
| Servicios externos | `config/services.php` |
| Scribe | `config/scribe.php` |
| Variables compartibles | `.env.example` |
| Valores efectivos locales | `.env` (secreto, no versionado) |

Tras cambiar `.env` o config cacheada, usa `php artisan config:clear`. No uses `config:cache` como parche para una instalación incompleta.

Última verificación: 2026-08-24.

Fuentes consultadas: `.env.example`, todos los archivos `config/*.php`, `bootstrap/app.php`, controladores, migraciones y manifiestos.
