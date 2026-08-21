# Plan progresivo de documentación para Codex

## Propósito

Construir una base de conocimiento técnica, breve y verificable que permita a un agente Codex localizar rápidamente el código relevante, comprender el impacto de un cambio y validar correcciones o nuevas funcionalidades sin tener que redescubrir todo el proyecto en cada tarea.

Este archivo es únicamente el plan de trabajo. No intenta documentar todavía la lógica interna ni sustituir la documentación pública de la API generada con Scribe.

## Alcance del recorrido estructural inicial

El proyecto es un backend Laravel 12 sobre PHP 8.2, con autenticación mediante Sanctum, documentación de API mediante Scribe y pruebas con Pest. La capa web/Vite es mínima y sirve principalmente vistas y recursos de documentación.

Áreas encontradas:

- `routes/api.php`: registro, inicio y cierre de sesión; recursos API de vehículos, cargas de gasolina, mantenimientos y recordatorios; endpoints de consulta para tipos y estados de vehículo y prioridades de recordatorio.
- `app/Http/Controllers/Api`: ocho controladores, organizados por autenticación, cuatro recursos principales y tres catálogos.
- `app/Http/Requests`: validadores separados para autenticación y operaciones `store`/`update`.
- `app/Http/Resources`: recursos JSON y colecciones para usuarios, entidades principales y catálogos.
- `app/Models`: usuario, vehículo, carga de gasolina, mantenimiento, recordatorio y sus catálogos.
- `app/Policies`: autorización para vehículos, cargas, mantenimientos y recordatorios.
- `database/migrations`: esquema de negocio y tablas de infraestructura de Laravel/Sanctum.
- `database/seeders` y `database/factories`: datos base para catálogos y factories todavía limitadas.
- `.scribe`, `config/scribe.php` y vistas publicadas de Scribe: documentación de endpoints existente.
- `tests/Feature` y `tests/Unit`: actualmente contienen solo pruebas de ejemplo.
- `README.md`: conserva el contenido inicial de Laravel y aún no describe este proyecto.

## Estructura documental objetivo

La documentación futura debería vivir en `docs/codex/` y mantener archivos pequeños, enlazados desde un índice:

```text
docs/codex/
├── README.md                  # Índice, orden de lectura y mapa rápido
├── 01-project-overview.md     # Propósito, alcance y vocabulario
├── 02-architecture.md         # Capas, dependencias y flujo de una petición
├── 03-domain-model.md         # Entidades, relaciones e invariantes
├── 04-api-map.md              # Rutas y trazabilidad entre capas
├── 05-auth-security.md        # Sanctum, ownership, policies y límites
├── 06-data-lifecycle.md       # Migraciones, seeders, factories y borrados
├── 07-local-development.md    # Instalación, comandos y configuración
├── 08-testing.md              # Estrategia, matriz y comandos de pruebas
├── 09-operations.md           # Logs, colas, caché, health check y despliegue
├── 10-change-playbooks.md     # Guías para cambios recurrentes
├── 11-known-risks.md          # Deuda, inconsistencias y decisiones pendientes
└── inventories/
    ├── routes.md
    ├── components.md
    ├── database.md
    └── configuration.md
```

Scribe seguirá siendo la referencia del contrato consumible de la API. `docs/codex/04-api-map.md` deberá explicar cómo se implementa ese contrato y enlazarlo, sin duplicar ejemplos extensos de requests/responses.

## Plan por etapas

### Paso 1 — Crear el índice y la ficha del proyecto

Objetivo: ofrecer contexto suficiente para orientar cualquier sesión nueva en menos de dos minutos.

Tareas:

- Crear `docs/codex/README.md` con orden de lectura, enlaces y reglas de actualización.
- Sustituir las suposiciones por una descripción confirmada del producto, sus usuarios y sus límites.
- Registrar versiones principales, dependencias relevantes y herramientas de desarrollo.
- Crear un glosario corto y fijar nombres canónicos en español e inglés usados por código y negocio.
- Señalar qué información es fuente de verdad: rutas, migraciones, código, tests o Scribe.

Resultado: `README.md` y `01-project-overview.md`.

Criterio de cierre: un agente nuevo puede explicar qué hace Veloce, qué módulos contiene y dónde empezar a buscar.

### Paso 2 — Inventariar componentes y arquitectura

Objetivo: describir cómo está dividido el sistema sin entrar aún en cada endpoint.

Tareas:

- Catalogar controladores, Form Requests, Resources/Collections, modelos, policies, providers y componentes de infraestructura.
- Dibujar el flujo `ruta → middleware → controlador → validación/autorización → modelo → resource`.
- Documentar convenciones observadas y excepciones: nombres, namespaces, route model binding, paginación y forma de respuestas.
- Identificar capas deliberadamente ausentes, por ejemplo servicios, repositorios, jobs o eventos, sin presentarlas automáticamente como defectos.
- Diferenciar código de negocio, código generado/publicado por Scribe y esqueleto estándar de Laravel.

Resultado: `02-architecture.md` e `inventories/components.md`.

Criterio de cierre: se puede determinar qué archivos suelen cambiar juntos para cada tipo de modificación.

### Paso 3 — Documentar modelo de dominio y persistencia

Objetivo: hacer explícita la estructura de datos y el ownership de cada entidad.

Tareas:

- Documentar entidades: `User`, `Vehicle`, `GasolineRefill`, `Maintenance`, `Reminder`, `VehicleType`, `VehicleStatus` y `ReminderPriority`.
- Registrar columnas, nulabilidad, tipos, casts, campos asignables y valores de catálogo.
- Mapear relaciones Eloquent, claves foráneas, cardinalidades y reglas de borrado en cascada.
- Explicar qué entidades pertenecen directamente al usuario y cuáles lo hacen a través del vehículo.
- Separar tablas de negocio de tablas internas de sesiones, caché, colas y tokens.
- Contrastar migraciones, modelos, requests y resources para listar discrepancias, sin corregirlas dentro de la tarea documental.

Resultado: `03-domain-model.md`, `06-data-lifecycle.md` e `inventories/database.md`.

Criterio de cierre: antes de cambiar una entidad se pueden anticipar migraciones, relaciones, validaciones, serialización y efectos de borrado.

### Paso 4 — Construir el mapa trazable de la API

Objetivo: conectar cada endpoint con todas las piezas que participan en su ejecución.

Tareas:

- Generar el inventario efectivo de rutas con método, URI, nombre, middleware y acción.
- Agrupar endpoints por autenticación, vehículos, cargas de gasolina, mantenimientos, recordatorios y catálogos.
- Para cada endpoint, enlazar controlador/método, Form Request, policy, modelos y Resource/Collection.
- Registrar filtros, ordenamiento, paginación, códigos de estado y estructura de error/respuesta.
- Comparar el inventario con los YAML de `.scribe/endpoints` y marcar cobertura o desactualización.
- Evitar copiar secretos, tokens reales o valores sensibles desde `.env`.

Resultado: `04-api-map.md` e `inventories/routes.md`.

Criterio de cierre: dado un endpoint, el agente puede recorrer de forma directa su implementación completa y saber qué contrato debe preservar.

### Paso 5 — Profundizar en autenticación, autorización y seguridad

Objetivo: reducir el riesgo de introducir accesos entre usuarios o cambios inseguros.

Tareas:

- Describir registro, login, emisión y revocación de tokens Sanctum.
- Documentar middleware público/protegido y límites `throttle` actuales.
- Crear una matriz policy × acción × recurso.
- Verificar y documentar cómo se aplica ownership en listados, creación, actualización, consulta y borrado.
- Registrar validación de archivos, campos sensibles, exposición mediante Resources y configuración de proxies/CORS si aplica.
- Mantener una lista separada de hallazgos que requieran corrección y pruebas de regresión.

Resultado: `05-auth-security.md` y entradas iniciales en `11-known-risks.md`.

Criterio de cierre: cualquier cambio de acceso puede evaluarse contra una matriz explícita de autenticación, autorización y pertenencia.

### Paso 6 — Documentar configuración y entorno local

Objetivo: lograr una instalación reproducible y diagnósticos rápidos.

Tareas:

- Documentar requisitos, `composer setup`, comandos de desarrollo, build frontend y generación de documentación Scribe.
- Inventariar variables de `.env.example` por propósito, obligatoriedad y valor seguro de desarrollo, nunca valores reales.
- Explicar conexiones de base de datos, filesystem, mail, caché, sesiones y colas configuradas.
- Registrar comandos de migración/seed y dependencias necesarias antes de ejecutar Artisan.
- Documentar el health endpoint `/up` y la función de Vite/Scribe en este backend.
- Actualizar el `README.md` público del repositorio a partir de esta información, manteniéndolo más breve que la guía para Codex.

Resultado: `07-local-development.md`, `inventories/configuration.md` y un README del proyecto actualizado.

Criterio de cierre: un entorno limpio puede instalarse, ejecutarse y verificarse siguiendo solo la documentación.

### Paso 7 — Diseñar la documentación de pruebas y verificación

Objetivo: dar a Codex una forma fiable de comprobar cambios según su impacto.

Tareas:

- Inventariar la cobertura real y dejar claro que las pruebas actuales son inicialmente ejemplos.
- Diseñar una matriz de pruebas Feature por endpoint, incluyendo éxito, validación, no autenticado, no autorizado y ownership cruzado.
- Diseñar pruebas Unit solo para reglas aislables que aporten valor.
- Definir factories/fixtures faltantes para cada entidad y datos de catálogo requeridos.
- Registrar comandos de test completo, archivo individual, filtro, formato y análisis estático si se incorpora.
- Añadir una lista de verificación proporcional al cambio: migraciones, rutas, Pint, Pest, Scribe y build.

Resultado: `08-testing.md`.

Criterio de cierre: cada clase de cambio tiene una estrategia de comprobación concreta y repetible.

### Paso 8 — Documentar operación y observabilidad

Objetivo: facilitar la investigación de fallos fuera del flujo HTTP feliz.

Tareas:

- Documentar canales de log, manejo global de excepciones y formato esperado de errores.
- Confirmar el uso real de colas, caché, sesiones, mail y almacenamiento; marcar lo configurado pero no utilizado.
- Registrar tareas programadas, workers y comandos propios si aparecen en el futuro.
- Describir generación/publicación de Scribe y del diagrama ER (`graph.png`).
- Crear una guía de diagnóstico por síntomas comunes, con comandos no destructivos.
- Documentar supuestos de despliegue solo cuando se confirmen en infraestructura o CI.

Resultado: `09-operations.md`.

Criterio de cierre: los fallos habituales pueden localizarse sin volver a inspeccionar toda la configuración.

### Paso 9 — Crear playbooks para cambios frecuentes

Objetivo: convertir el conocimiento anterior en procedimientos cortos para implementar con seguridad.

Playbooks mínimos:

- Añadir o modificar un campo de una entidad.
- Añadir un endpoint o una acción a un recurso existente.
- Crear un recurso de negocio completo.
- Cambiar filtros, orden o paginación.
- Modificar permisos u ownership.
- Añadir un valor de catálogo o cambiar un seeder.
- Modificar un Resource sin romper consumidores.
- Corregir un bug con prueba de regresión.

Cada playbook debe incluir archivos probables, preguntas previas, pasos, riesgos y comandos de verificación.

Resultado: `10-change-playbooks.md`.

Criterio de cierre: las tareas recurrentes cuentan con una checklist trazable a la arquitectura y a los tests.

### Paso 10 — Validar, depurar y mantener la documentación

Objetivo: evitar que la documentación se convierta en una segunda fuente de verdad desactualizada.

Tareas:

- Revisar enlaces, rutas de archivos, nombres de clases y comandos contra el repositorio.
- Ejecutar periódicamente `route:list`, tests, generación Scribe y cualquier validador documental adoptado.
- Añadir en cada documento “última verificación” y fuentes de código consultadas.
- Crear una checklist de actualización para pull requests que cambien API, esquema, autenticación, configuración u operación.
- Conservar en `11-known-risks.md` solo hallazgos confirmados, con evidencia, impacto y estado.
- Archivar o corregir documentación obsoleta en el mismo cambio que modifica el comportamiento.

Resultado: documentación enlazada, revisada y con una política de mantenimiento.

Criterio de cierre: no existen enlaces rotos ni afirmaciones importantes sin una fuente comprobable en código, configuración o tests.

## Orden recomendado de ejecución

Los pasos 1 a 4 forman el núcleo mínimo y deben realizarse en orden. Los pasos 5 y 6 pueden avanzar después en paralelo conceptual, pero deben cerrarse antes de redactar playbooks. El paso 7 debe acompañar cualquier ampliación real de pruebas. Los pasos 8 y 9 dependen del mapa arquitectónico consolidado. El paso 10 se aplica al terminar cada entrega y permanece activo de forma continua.

## Reglas para que la documentación sea útil a Codex

- Preferir tablas, mapas y listas concretas frente a explicaciones generales de Laravel.
- Enlazar siempre a rutas reales del repositorio y nombrar la fuente de verdad.
- Diferenciar claramente hechos verificados, inferencias y decisiones pendientes.
- No incluir secretos, credenciales, datos personales ni contenido de `.env`.
- No duplicar documentación generada por Scribe; enlazar y aportar trazabilidad interna.
- Registrar tanto la convención como las excepciones encontradas.
- Mantener cada documento enfocado y permitir una lectura progresiva desde el índice.
- Actualizar documentación y pruebas en el mismo cambio funcional que las afecta.

## Definición global de terminado

La documentación estará suficientemente madura cuando un agente Codex pueda, sin exploración exhaustiva previa:

1. localizar todas las capas afectadas por una petición de cambio;
2. explicar el modelo de datos y las reglas de pertenencia implicadas;
3. preservar el contrato API y sus respuestas;
4. identificar riesgos de seguridad y compatibilidad;
5. ejecutar una verificación proporcional al cambio; y
6. actualizar la documentación correcta como parte de la entrega.
