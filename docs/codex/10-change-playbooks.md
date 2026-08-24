# Playbooks para cambios frecuentes

Estos procedimientos orientan el trabajo; no sustituyen leer el código afectado. Antes de empezar, identifica el contrato vigente en [Mapa de la API](04-api-map.md), el esquema en [Modelo de dominio](03-domain-model.md), los límites de acceso en [Seguridad](05-auth-security.md) y la verificación en [Pruebas](08-testing.md).

## Reglas comunes

1. Define comportamiento observable y compatibilidad antes de editar.
2. Recorre `ruta → middleware → controlador → Request/policy → modelo → Resource`.
3. Cambia migraciones de forma evolutiva; no reescribas una ya aplicada en entornos compartidos.
4. Mantén ownership en consulta, binding y FKs, no solo en validación `exists`.
5. Actualiza código, tests, Scribe y documentos en el mismo cambio.
6. Usa una base de pruebas desechable para comandos destructivos.
7. Revisa el diff generado; no confirmes `.env`, tokens, logs ni datos reales.

## 1. Añadir o modificar un campo

### Preguntas previas

- ¿Es requerido, nullable, tiene default o debe calcularse?
- ¿Cómo se migran filas existentes y cómo se revierte?
- ¿El cliente puede escribirlo, leerlo o ambas cosas?
- ¿Es sensible, único, indexable, archivo o FK?
- ¿Cambian casts, precisión, timezone o compatibilidad MySQL/SQLite?

### Archivos probables

- Nueva migración en `database/migrations/`.
- Modelo en `app/Models/`.
- Requests `Store*` y `Update*`.
- Resource, Collection y controlador si requiere carga/transformación.
- Factory, seeder, tests Feature y anotaciones Scribe.
- `03-domain-model.md`, `06-data-lifecycle.md` e `inventories/database.md`.

### Pasos

1. Contrasta tipo/nulabilidad entre migración, Request y datos actuales.
2. Añade migración segura para filas existentes; separa backfill y NOT NULL si hace falta.
3. Actualiza `$fillable`, casts, hidden y relaciones sin exponer el campo por accidente.
4. Ajusta reglas de store/update; diferencia omisión, null y valor vacío.
5. Añade/elimina el campo en Resource deliberadamente.
6. Actualiza factories y casos de éxito, validación, serialización y rollback.
7. Regenera Scribe y documentación de datos.

### Riesgos

Errores de integridad como los ya documentados en vehículos, pérdida de precisión, exposición sensible, migraciones bloqueantes y diferencias SQLite/MySQL.

### Verificación

```bash
php artisan migrate --pretend
php artisan test tests/Feature/Api
./vendor/bin/pint --test
php artisan scribe:generate
```

Ejecuta migración/rollback real solo en una base desechable y valida datos previos al imponer constraints.

## 2. Añadir un endpoint o acción a un recurso existente

### Preguntas previas

- ¿Por qué no encaja en una acción REST existente?
- ¿Es público, autenticado, propietario o global?
- ¿Método, URI, nombre, status y respuesta preservan convenciones?
- ¿Es idempotente y necesita throttle?

### Archivos probables

`routes/api.php`, controlador, policy, Form Request, Resource/Collection, tests, PHPDoc Scribe, `04-api-map.md` e `inventories/routes.md`.

### Pasos

1. Define método/URI/nombre y contrato antes de implementar.
2. Coloca la ruta en el grupo correcto; evita colisiones con `apiResource` y binding.
3. Añade método de controlador delgado y Request si recibe entrada.
4. Añade ability de policy o autorización explícita; acota consultas de listado.
5. Reutiliza Resource o crea uno específico si la representación difiere justificadamente.
6. Prueba éxito, 401, ownership 403/404, validación y recurso inexistente.
7. Documenta con Scribe, regenera y revisa route list/specs.

### Riesgos

Ruta accidentalmente pública, binding con parámetro distinto al usado por `authorizeResource`, status inconsistente, N+1 y Scribe incompleto.

### Verificación

```bash
php artisan route:list --path=api
php artisan test --filter='nombre de la nueva acción'
./vendor/bin/pint --test
php artisan scribe:generate
```

## 3. Crear un recurso de negocio completo

### Preguntas previas

- ¿Quién lo posee: usuario directo, vehículo o catálogo global?
- ¿Cuáles son cardinalidad, cascadas y lifecycle?
- ¿Qué acciones se exponen y qué relaciones se incluyen?
- ¿Necesita archivos, jobs, eventos o transacciones?

### Archivos probables

Migración, modelo, factory, Requests, controlador API, policy, Resource/Collection, ruta, tests, Scribe, seeders si hay catálogo y todos los mapas/inventarios relacionados.

### Pasos

1. Diseña esquema, FK, ownership y borrado antes de generar clases.
2. Crea migración/modelo y factory válida con todos los NOT NULL.
3. Implementa Requests separados; `exists` no reemplaza ownership.
4. Implementa policy y enlázala con nombre exacto de parámetro.
5. Implementa controlador con listados acotados y eager loading necesario.
6. Define Resource/Collection, status, paginación y mensajes.
7. Registra rutas bajo Sanctum salvo decisión pública explícita.
8. Cubre matriz Feature completa y acceso cruzado.
9. Regenera Scribe, ERD y documentación Codex.

### Riesgos

IDOR, cascadas destructivas, factory inválida, formato divergente, archivos huérfanos y abstracciones nuevas inconsistentes con la arquitectura directa actual.

### Verificación

```bash
php artisan migrate:fresh --seed   # solo DB desechable
php artisan route:list --path=api
php artisan test tests/Feature/Api
./vendor/bin/pint --test
php artisan scribe:generate
php artisan generate:erd
```

## 4. Cambiar filtros, orden o paginación

### Preguntas previas

- ¿Cuáles query parameters, defaults, límites y valores inválidos?
- ¿El orden es estable y necesita desempate por ID?
- ¿Las columnas están indexadas y siempre se conserva ownership?
- ¿Cambiará la forma actual de metadatos?

### Archivos probables

Controlador, Request dedicado o validación de query, Collection, migración de índice, tests, PHPDoc/Scribe, `04-api-map.md` e `inventories/routes.md`.

### Pasos

1. Especifica nombres, tipos, enums, default y máximo de `per_page`.
2. Construye la consulta desde el scope de ownership; aplica filtros después.
3. Usa allowlists para columnas/direcciones de orden, nunca input como SQL libre.
4. Añade desempate determinista y eager loading.
5. Preserva metadatos existentes o versiona el cambio.
6. Prueba combinaciones, límites, valores inválidos, páginas vacías y aislamiento entre usuarios.
7. Documenta query params en Scribe.

### Riesgos

Fuga de datos al reemplazar el scope, inyección por orden dinámico, consultas lentas, resultados inestables y ruptura de camelCase/snake_case actual.

### Verificación

```bash
php artisan test --filter='filtro|paginación|orden'
./vendor/bin/pint --test
php artisan scribe:generate
```

Para cambios de consulta sensibles al motor, prueba también contra MySQL.

## 5. Modificar permisos u ownership

### Preguntas previas

- ¿Qué actor, acción y recurso se habilita o bloquea?
- ¿El recurso pertenece directamente o mediante vehículo?
- ¿Debe una denegación ser 403 o una ocultación 404?
- ¿Afecta listados, creación, reasignación y cascadas además del binding?

### Archivos probables

`routes/api.php`, policy, controlador/scopes, Request, relaciones, tests, `05-auth-security.md`, `11-known-risks.md` y mapa API.

### Pasos

1. Actualiza la matriz policy × acción antes del código.
2. Mantén autenticación y revisa throttle/abilities.
3. Modifica policy para instancias y scope para listados.
4. Comprueba ownership de toda FK entrante y de reasignaciones.
5. Evita aceptar `user_id` del cliente salvo caso diseñado y autorizado.
6. Crea fixtures con dos usuarios y prueba todas las operaciones cruzadas.
7. Revisa Resources, logs y Scribe para no filtrar información.

### Riesgos

IDOR, listados globales accidentales, diferencia de respuestas como oráculo, escalación mediante mass assignment y tests que solo cubren al propietario.

### Verificación

```bash
php artisan test --filter='ownership|ajeno|no autenticado'
php artisan route:list --path=api
./vendor/bin/pint --test
```

No cierres sin pruebas positivas y negativas.

## 6. Añadir o cambiar un valor de catálogo/seeder

### Preguntas previas

- ¿Se añade, renombra, fusiona o elimina?
- ¿Hay filas que lo referencian y qué hace la cascada?
- ¿El texto es identidad estable o se necesita código/slug?
- ¿Debe cambiar una factory o consumidor móvil/web?

### Archivos probables

Seeder, migración si cambia constraint/estructura, modelo/factory, tests de catálogo, Requests con FK, Scribe, `03-domain-model.md` e inventario DB.

### Pasos

1. Busca referencias y variantes de acentos/capitalización.
2. Para renombrar, actualiza la fila existente; no confíes en `updateOrCreate` con el nuevo texto porque dejará la anterior.
3. Para eliminar, decide reasignación antes de borrar: las cascadas actuales pueden eliminar vehículos o recordatorios.
4. Alinea seeder y factory; preserva idempotencia.
5. Ejecuta seeder dos veces en DB desechable y comprueba conjunto/relaciones.
6. Actualiza documentación y consumidores.

### Riesgos

Duplicados sin unique, IDs asumidos por clientes, cascadas masivas y datos de tests distintos a producción.

### Verificación

```bash
php artisan db:seed
php artisan db:seed
php artisan test --filter='catálogo|seeder'
./vendor/bin/pint --test
```

Los seeders modifican datos: ejecútalos primero en una copia o base desechable cuando cambie semántica existente.

## 7. Modificar un Resource sin romper consumidores

### Preguntas previas

- ¿El campo se añade, renombra, elimina o cambia tipo/null/formato?
- ¿La relación está siempre cargada o usa `whenLoaded`?
- ¿Se altera `data`, paginación, fecha, decimal o URL?
- ¿Scribe/OpenAPI y clientes conocen la forma actual?

### Archivos probables

Resource/Collection, controlador/eager loading, modelo/casts, tests JSON, Scribe y mapa API.

### Pasos

1. Captura con test la respuesta actual relevante.
2. Clasifica el cambio como compatible o breaking; evita renombres/eliminaciones silenciosas.
3. Ajusta eager loading para no crear N+1 ni campos condicionales inesperados.
4. Usa Resources anidados explícitos cuando necesites controlar campos.
5. Afirma tipos, null, formatos de fecha/decimal, URLs y metadatos completos.
6. Regenera Scribe y revisa OpenAPI/Postman.

### Riesgos

Pérdida de hora como en `ReminderResource`, exposición de timestamps/FKs por modelos crudos, doble envoltura `data`, N+1 y cambios de tipo string/número.

### Verificación

```bash
php artisan test --filter='resource|respuesta|json'
php artisan scribe:generate
./vendor/bin/pint --test
```

## 8. Corregir un bug con prueba de regresión

### Preguntas previas

- ¿Cuál es la entrada mínima y el resultado real/esperado?
- ¿Es defecto de código, datos, configuración o contrato desactualizado?
- ¿Qué usuarios/datos/driver lo reproducen?
- ¿La corrección cambia comportamiento público?

### Archivos probables

Test Feature/Unit que reproduce, archivo causante, factory/fixture, Scribe/documento afectado y `11-known-risks.md` si ya estaba registrado.

### Pasos

1. Reproduce con el test más cercano al comportamiento público; confirma que falla por la causa esperada.
2. Reduce el caso y registra status, JSON, DB/Storage y actor.
3. Aplica la corrección mínima sin normalizar diferencias no relacionadas.
4. Ejecuta el test nuevo, vecinos y suite completa.
5. Añade casos límite razonables y prueba MySQL si el bug depende del motor.
6. Actualiza contrato/documentos y marca el riesgo resuelto con evidencia.

### Riesgos

Test que pasa antes del fix, fixture irreal, corregir el síntoma en Resource cuando la causa está en esquema/ownership y ampliar alcance sin intención.

### Verificación

```bash
php artisan test --filter='nombre exacto de la regresión'
composer test
./vendor/bin/pint --test
npm run build   # si afecta vistas/assets
```

## Cierre de cualquier playbook

- `git diff --check` no reporta errores.
- No hay cambios o secretos ajenos en el diff.
- Tests y formato proporcionales pasan.
- Rutas, migraciones, Scribe y build se verificaron cuando aplican.
- Documentación e inventarios afectados están actualizados.
- Riesgos nuevos o resueltos tienen evidencia y estado.

Si Artisan no arranca en otro entorno, ejecuta `composer install` y resuelve el error concreto; no declares verificación exitosa sin ejecutar los comandos requeridos.

Última verificación: 2026-08-24.

Fuentes consultadas: arquitectura, modelo de dominio, mapa API, seguridad, ciclo de datos, desarrollo local, pruebas, operación, riesgos conocidos y código enlazado por esos documentos.
