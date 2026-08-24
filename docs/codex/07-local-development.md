# Desarrollo local

## Requisitos

- PHP 8.2 o posterior compatible con `composer.lock` y extensiones requeridas por Composer/Laravel.
- Composer 2.
- Node.js y npm compatibles con Vite 7; usa el lock de npm como referencia reproducible.
- MySQL accesible para los valores predeterminados de `.env.example`, o una conexión Laravel alternativa configurada deliberadamente.
- Graphviz solo para generar `graph.png` con el paquete ERD.

Laravel, Sanctum, Scribe, Pest, Pint y el generador ERD se instalan mediante Composer. Vite, Tailwind y recursos web se instalan mediante npm.

## Instalación recomendada

Desde la raíz del repositorio:

```bash
composer install
cp .env.example .env
php artisan key:generate
npm ci
```

Antes de migrar, crea la base indicada por `DB_DATABASE` y ajusta en `.env` host, puerto y credenciales. Después:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
```

`--seed` es necesario para cargar tipos/estados de vehículo y prioridades. `storage:link` permite servir las fotos guardadas en el disco `public`.

Nunca reutilices `APP_KEY`, credenciales o tokens de otro entorno. No confirmes `.env` en Git.

## Script de setup incluido

```bash
composer setup
```

El script ejecuta `composer install`, crea `.env` si falta, genera `APP_KEY`, corre `migrate --force`, instala npm y compila. Tiene límites relevantes:

- presupone que la base configurada ya existe y es accesible;
- usa `npm install`, no `npm ci`;
- no ejecuta seeders;
- no crea `storage:link`.

Por eso, tras usarlo ejecuta al menos:

```bash
php artisan db:seed
php artisan storage:link
```

No uses `migrate:fresh` como instalación habitual: borra todas las tablas y datos.

## Ejecutar en desarrollo

```bash
composer dev
```

Arranca en paralelo:

- servidor HTTP de Artisan;
- `queue:listen` con un intento;
- Laravel Pail para logs;
- servidor Vite con recarga.

El proceso termina todos los servicios si uno falla. Para investigar por separado:

```bash
php artisan serve
npm run dev
php artisan queue:listen --tries=1 --timeout=0
php artisan pail --timeout=0
```

La API queda normalmente en `http://127.0.0.1:8000/api`. `/` sirve la vista inicial y `/up` es el health check de Laravel.

## Verificación rápida

```bash
curl -i http://127.0.0.1:8000/up
php artisan migrate:status
composer test
./vendor/bin/pint --test
npm run build
```

Las pruebas actuales son ejemplos; consulta [Pruebas y verificación](08-testing.md) para la estrategia completa. El build sigue siendo relevante porque Scribe/Vite publican recursos web aunque el producto sea principalmente backend.

## Documentación Scribe

Genera los YAML, la vista Blade, assets, Postman y OpenAPI con:

```bash
php artisan scribe:generate
```

Con `config/scribe.php` actual (`type: laravel`, `add_routes: true`), una aplicación en ejecución expone:

- `/docs`: interfaz HTML;
- `/docs.postman`: colección Postman;
- `/docs.openapi`: especificación OpenAPI.

Los YAML persistidos viven en `.scribe/endpoints/`, la vista en `resources/views/scribe/`, assets en `public/vendor/scribe/` y Postman/OpenAPI en `storage/app/private/scribe/`. La generación verificada cubre las 23 acciones y su autenticación; revisa el diff y completa respuestas explícitas antes de tratar OpenAPI como contrato exhaustivo.

`SCRIBE_AUTH_KEY`, consumida por configuración pero ausente en `.env.example`, es opcional para response calls. Si se usa, debe ser un token dedicado y nunca versionarse ni copiarse a documentación.

## Diagrama entidad-relación

El paquete detecta modelos Eloquent y genera por defecto `graph.png`:

```bash
php artisan generate:erd
```

Requiere que `dot` de Graphviz esté instalado. El diagrama refleja relaciones de modelos, no sustituye migraciones ni `inventories/database.md`.

## Configuración local esencial

| Área | Default de `.env.example` | Acción local |
| --- | --- | --- |
| Aplicación | `APP_ENV=local`, debug activo, URL localhost | Cambiar `APP_NAME` a Veloce; conservar debug solo localmente |
| Base de datos | MySQL en `127.0.0.1:3306`, DB `veloce`, user root | Crear DB y definir credenciales locales |
| Caché | Database | Requiere migraciones de `cache` |
| Sesión | Database, 120 minutos, sin cifrado de payload | Requiere tabla `sessions`; Sanctum API usa bearer tokens en flujos actuales |
| Cola | Database | Requiere tablas jobs; `composer dev` inicia listener aunque no hay jobs propios |
| Mail | Log | No envía correo externo; escribe en logs |
| Filesystem | Default local privado | Fotos fuerzan disco `public`; requiere enlace simbólico |
| Broadcast | Log | Configurado, sin uso de negocio confirmado |
| Logs | Stack → single, nivel debug | `storage/logs/laravel.log`; no usar debug en producción |

Consulta [inventario de configuración](inventories/configuration.md) para todas las variables.

## Estado conocido del workspace verificado

El 2026-08-24 se completó `composer install`: Artisan y `route:list` arrancan correctamente. La suite llega a ejecutarse, pero una prueba Feature falla porque el entorno de testing no define `APP_KEY`; consulta `08-testing.md` y `11-known-risks.md`.

Última verificación: 2026-08-24.

Fuentes consultadas: manifiestos y locks, scripts Composer/npm, `.env.example`, configuración Laravel/Scribe, Vite, rutas, migraciones y documentación oficial de Scribe y del generador ERD.
