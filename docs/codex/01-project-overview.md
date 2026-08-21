# Ficha del proyecto Veloce

## Resumen verificable

Veloce es una API backend para que una cuenta autenticada registre sus vehículos y gestione información asociada: cargas de gasolina, mantenimientos y recordatorios. También expone catálogos de tipos y estados de vehículo y prioridades de recordatorio.

La evidencia disponible confirma como usuario del sistema a la persona titular de una cuenta que administra sus propios vehículos. El repositorio no contiene una definición comercial, perfiles administrativos, organizaciones, flotas compartidas ni otros roles; por tanto, esta ficha no presupone que existan.

## Capacidades actuales

| Módulo de negocio | Responsabilidad comprobada | Entrada principal |
| --- | --- | --- |
| Autenticación | Registrar cuenta, iniciar sesión, emitir tokens y cerrar sesiones | `routes/api.php`, `AuthController` |
| Vehículos | Crear, listar, consultar, actualizar y eliminar vehículos del usuario | `VehicleController`, `Vehicle` |
| Cargas de gasolina | Crear, listar, actualizar y eliminar consumos asociados a vehículos | `GasolineRefillController`, `GasolineRefill` |
| Mantenimientos | Crear, listar, actualizar y eliminar intervenciones asociadas a vehículos | `MaintenanceController`, `Maintenance` |
| Recordatorios | Crear, listar, actualizar y eliminar avisos fechados y priorizados | `ReminderController`, `Reminder` |
| Catálogos | Consultar tipos y estados de vehículo y prioridades de recordatorio | Controladores y modelos de catálogo |

Salvo registro e inicio de sesión, las rutas de `routes/api.php` requieren autenticación Sanctum. La autorización y las reglas precisas de pertenencia se documentarán en el paso 5; no deben inferirse únicamente de este resumen.

## Límites confirmados del alcance actual

- Es un backend API Laravel; la capa Vite/web presente no evidencia una aplicación cliente del producto.
- No hay endpoints de administración de usuarios, roles, organizaciones ni vehículos compartidos.
- No hay endpoints para estaciones de servicio, proveedores, pagos, telemetría, rutas o geolocalización.
- No hay acciones API `show` individuales para cargas de gasolina, mantenimientos ni recordatorios; vehículos sí dispone de ellas.
- Los catálogos se consultan mediante API y sus valores iniciales proceden de seeders; no hay rutas para administrarlos.
- El código no evidencia todavía notificaciones programadas para los recordatorios. Un recordatorio persistido no equivale por sí solo a un envío.
- Las pruebas Feature y Unit existentes son archivos de ejemplo, no una cobertura funcional del dominio.

Estas frases describen lo observable en el repositorio al verificar esta ficha; no deciden qué funciones puede incorporar el producto en el futuro.

## Modelo mental mínimo

`User` es la cuenta autenticable y posee `Vehicle`. Cada `Vehicle` se clasifica mediante `VehicleType` y `VehicleStatus`, y agrupa muchos `GasolineRefill`, `Maintenance` y `Reminder`. Cada `Reminder` referencia una `ReminderPriority`.

Para detalles sobre columnas, cardinalidades, cascadas e invariantes se deberá consultar [modelo de dominio](03-domain-model.md). Hasta entonces, las migraciones son la fuente de verdad del esquema.

## Tecnología y herramientas

| Componente | Versión declarada o bloqueada | Función en el proyecto |
| --- | --- | --- |
| PHP | `^8.2` | Runtime requerido; el entorno verificado usa PHP 8.3.32 |
| Laravel Framework | `^12.0`; lock `v12.67.0` | Framework HTTP, aplicación y persistencia |
| Laravel Sanctum | `^4.3`; lock `v4.3.2` | Tokens personales y autenticación de la API |
| Scribe | `^5.10`; lock `5.10.0` | Generación de documentación consumible de la API |
| Pest | `^3.8`; lock `v3.8.7` | Pruebas automatizadas |
| Laravel Pint | `^1.24`; lock `v1.30.5` | Formato del código PHP |
| ER Diagram Generator | `^6.0`; lock `6.0.0` | Generación auxiliar del diagrama entidad-relación |
| Vite | `^7.0.7` | Build de recursos web/documentales mínimos |
| Tailwind CSS | `^4.0.0` | Estilos de la capa web mínima |
| Composer / npm | Manifiestos y locks del repositorio | Gestión de dependencias y scripts de desarrollo |

Los números exactos bloqueados pueden cambiar al actualizar dependencias. Consulta siempre `composer.lock` y `package-lock.json`; las restricciones compatibles viven en sus manifiestos respectivos.

## Glosario y nombres canónicos

Usa el término español al hablar del negocio y el identificador inglés al buscar o escribir código.

| Español canónico | Inglés/código canónico | Significado |
| --- | --- | --- |
| usuario / cuenta | `User` | Identidad autenticable que posee vehículos |
| vehículo | `Vehicle` | Unidad principal gestionada por el usuario |
| tipo de vehículo | `VehicleType` | Catálogo que clasifica un vehículo |
| estado del vehículo | `VehicleStatus` | Catálogo del estado actual de un vehículo |
| carga de gasolina | `GasolineRefill` | Registro de monto, litros, fecha y gasolinera; evita alternar con “recarga” en títulos nuevos |
| mantenimiento | `Maintenance` | Trabajo o intervención realizado sobre un vehículo |
| recordatorio | `Reminder` | Aviso fechado asociado a un vehículo |
| prioridad del recordatorio | `ReminderPriority` | Catálogo de prioridad de un recordatorio |
| pertenencia / propietario | ownership / owner | Regla que limita un recurso a la cuenta correspondiente |
| recurso JSON | Resource / Collection | Transformador de la respuesta API; no confundir con recurso de negocio |
| solicitud de formulario | Form Request | Clase Laravel de validación y, cuando aplique, autorización de entrada |

El código y las rutas usan `gasoline-refills`, mientras algunos comentarios dicen “recarga”. Para documentación de negocio nueva se fija “carga de gasolina”, en línea con el grupo público de Scribe y el plan; `GasolineRefill` permanece como nombre técnico.

## Dónde empezar según la tarea

- Cambio de endpoint: empieza en `routes/api.php`, sigue al controlador, Form Request, policy, modelo y Resource, y contrasta Scribe.
- Cambio de datos: empieza en `database/migrations/`, y contrasta modelo, relaciones, Requests, Resources y seeders.
- Cambio de acceso: empieza en la ruta/middleware y continúa por policy, controlador y consultas de ownership.
- Error de respuesta: revisa controlador y Resource/Collection; usa Scribe como contrato documentado y tests como evidencia de regresión.
- Cambio de dependencias o entorno: consulta primero los manifiestos y locks; la guía reproducible se añadirá en el paso 6.

## Hechos, inferencias y preguntas abiertas

Hecho: los módulos, endpoints y relaciones resumidos arriba existen en código. Inferencia prudente: el producto está orientado al control personal de vehículos porque todos los recursos de negocio parten de una cuenta y sus vehículos. Pregunta abierta: el repositorio no documenta todavía visión comercial, clientes objetivo fuera de esa cuenta, despliegue ni integraciones externas.

Si una tarea depende de esas preguntas, debe obtenerse una decisión del responsable del producto en vez de convertir la inferencia en requisito.

Última verificación: 2026-08-21.

Fuentes consultadas: `routes/api.php`, `app/Http/Controllers/Api/`, `app/Models/`, `database/migrations/`, `database/seeders/`, `composer.json`, `composer.lock`, `package.json`, `package-lock.json`, `config/scribe.php`, `.scribe/` y `tests/`.
