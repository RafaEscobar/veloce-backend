# Veloce Backend

API Laravel para registrar vehículos y gestionar sus cargas de gasolina, mantenimientos y recordatorios. Usa Laravel Sanctum para autenticación mediante tokens personales y Scribe para documentar el contrato HTTP.

## Requisitos

- PHP 8.2+
- Composer 2
- Node.js y npm compatibles con Vite 7
- MySQL, según `.env.example`

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
npm ci
```

Crea la base configurada en `.env` y después ejecuta:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
```

El seeding carga los catálogos necesarios. El enlace de storage permite servir fotos de vehículos.

## Desarrollo

```bash
composer dev
```

Este comando inicia servidor Laravel, listener de colas, Pail y Vite. La API se sirve normalmente bajo `http://127.0.0.1:8000/api` y el health check está en `/up`.

## Verificación

```bash
composer test
./vendor/bin/pint --test
npm run build
```

## Documentación API

```bash
php artisan scribe:generate
```

Con el servidor en ejecución, abre `/docs`. Scribe también publica `/docs.postman` y `/docs.openapi`.

La documentación técnica para agentes y mantenedores comienza en [docs/codex/README.md](docs/codex/README.md). La guía local detallada está en [docs/codex/07-local-development.md](docs/codex/07-local-development.md).
