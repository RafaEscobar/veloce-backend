# Autenticación, autorización y seguridad

## Modelo de autenticación

La API usa Laravel Sanctum mediante tokens personales. `User` incorpora `HasApiTokens`; las rutas protegidas están dentro de `auth:sanctum`.

### Registro

`POST /api/register` está limitado a 3 solicitudes por minuto. `StoreRegisterRequest` exige nombre, apellido, email único y contraseña string de al menos 8 caracteres. `AuthController` crea el usuario, hashea la contraseña y emite `createToken('auth_token')`. Retorna una sola vez el token en texto plano junto con `UserResource` y status 201.

El controlador usa `Hash::make` y el modelo también declara cast `hashed`; Laravel evita volver a hashear un valor ya reconocido como hash. `BCRYPT_ROUNDS` vale 12 en `.env.example`.

### Login

`POST /api/login` está limitado a 5 solicitudes por minuto. Valida email/password, usa `Auth::attempt` y, si tiene éxito, emite un token personal nuevo. Credenciales inválidas producen `ValidationException` sobre `email`.

Cada registro o login añade otro token; no reemplaza tokens previos.

### Logout

`POST /api/logout` requiere Sanctum y ejecuta `user()->tokens()->delete()`. Revoca todos los tokens personales de la cuenta, no solo el token usado en la petición. La respuesta es 200 con `message`.

## Propiedades de los tokens

| Propiedad | Configuración actual | Consecuencia |
| --- | --- | --- |
| Nombre | `auth_token` | Todos los tokens comparten nombre descriptivo |
| Abilities | No se pasan a `createToken` | Sanctum usa `['*']` por defecto |
| Expiración | `config/sanctum.php`: `null`; no se pasa expiración | No caducan automáticamente |
| Prefijo | `SANCTUM_TOKEN_PREFIX`, vacío por defecto | Puede configurarse para secret scanning |
| Persistencia | `personal_access_tokens`, token hasheado | El texto plano solo se entrega al emitirlo |
| Revocación | Logout borra colección completa | Cierra todas las sesiones basadas en PAT |

La protección del token queda a cargo del cliente. No debe escribirse en logs, documentación, fixtures ni repositorio.

## Matriz de acceso

`Sí` en acciones de instancia significa “solo si pertenece al usuario”; `Global` significa catálogo compartido entre cuentas autenticadas.

| Recurso | Listar | Crear | Ver instancia | Actualizar | Eliminar | Control efectivo |
| --- | --- | --- | --- | --- | --- | --- |
| Vehículo | Sí, filtrado | Sí, fuerza `user_id` | Propio | Propio | Propio | `VehiclePolicy` + relación del usuario |
| Carga | Sí, por vehículos propios | En vehículo propio | Sin ruta show | Propia | Propia | `GasolineRefillPolicy` + `findOrFail` |
| Mantenimiento | Sí, por vehículos propios | En vehículo propio | Sin ruta show | Propio; nuevo vehículo también propio | Propio | `MaintenancePolicy` + `findOrFail` |
| Recordatorio | Sí, por vehículos propios | En vehículo propio | Sin ruta show | Propio; nuevo vehículo también propio | Propio | `ReminderPolicy` + `findOrFail` |
| Tipos/estados/prioridades | Global | Sin ruta | Global vía listado | Sin ruta | Sin ruta | Solo `auth:sanctum` |

Las policies implementan `viewAny` y `create` como `true` para cualquier `User` autenticado. La restricción real de listados y del vehículo elegido ocurre en controladores.

## Ownership por operación

### Listados

Vehículos usa `request.user.vehicles()`. Los tres recursos hijos aplican `whereIn('vehicle_id', request.user.vehicles().pluck('id'))`. Por ello no dependen de que `viewAny()` filtre filas.

### Creación

- Vehículo ignora cualquier `user_id` externo porque el Request no lo admite y el controlador fuerza el ID autenticado.
- Carga, mantenimiento y recordatorio validan que el ID exista y después buscan ese ID dentro de `user()->vehicles()`.
- Las prioridades y clasificaciones son referencias globales válidas para cualquier cuenta.

### Binding, actualización y borrado

`authorizeResource` enlaza binding y policy. Vehículo compara `vehicle.user_id`; recursos hijos comparan `resource.vehicle.user_id`. En mantenimiento/recordatorio, si update cambia `vehicle_id`, el controlador valida también el nuevo propietario. Carga no acepta `vehicle_id` en su Update Request.

Un recurso ajeno existente llega al binding y debe terminar en 403 por policy. Un `vehicle_id` ajeno enviado como relación pasa `exists` y termina en 404 mediante la relación del usuario.

## Middleware y límites

| Rutas | Middleware explícito |
| --- | --- |
| Register | `throttle:3,1` |
| Login | `throttle:5,1` |
| Logout, recursos y catálogos | `auth:sanctum` |

No hay throttle explícito en las 21 rutas protegidas, ni RateLimiter propio en providers. No existen roles, permisos administrativos ni abilities diferenciadas.

`bootstrap/app.php` confía en todos los proxies (`at: '*'`) y acepta headers forwarded de IP, host, puerto y protocolo. Esta configuración solo es segura si el servicio no puede recibir tráfico directo fuera de proxies confiables que limpien esos headers.

No hay `config/cors.php` local ni personalización CORS en `bootstrap/app.php`; el comportamiento efectivo depende del framework/entorno y debe verificarse en despliegue antes de integrar navegadores.

## Validación de archivos

`StoreVehicleRequest` y `UpdateVehicleRequest` usan `image` y máximo 2048 KB. El controlador almacena en `vehicles/` del disco `public`, reemplaza la foto anterior en update y la elimina al borrar mediante el endpoint.

El disco tiene visibilidad pública y `VehicleResource` genera una URL `/storage/...`. La descarga no pasa por `auth:sanctum` ni por `VehiclePolicy`: conocer la URL permite acceder al archivo. Además, borrados en cascada pueden dejar archivos huérfanos.

## Campos sensibles y exposición

- `User` oculta password y remember token; `UserResource` solo expone ID, nombre, apellido y email.
- El token aparece únicamente en respuestas de registro/login por diseño.
- `VehicleResource` expone placas, VIN/serial, foto, combustibles y aceite al propietario.
- Resources hijos exponen sus `vehicle_id`; no contienen usuario, email ni token.
- `VehicleResource` puede serializar relaciones cargadas directamente, no siempre mediante Resources hijos, por lo que el detalle puede incluir timestamps y FKs adicionales de modelos propios.
- `.env` no debe copiarse; la documentación usa solamente nombres/defaults seguros de `.env.example`.

## Respuestas de seguridad

| Caso | Resultado esperado |
| --- | --- |
| Sin autenticación | 401 JSON predeterminado |
| Recurso enlazado ajeno | 403 por policy |
| Vehículo ajeno usado como FK | 404 por relación acotada |
| ID inexistente en `exists` | 422 |
| Validación general | 422 |
| Credenciales incorrectas | 422 |
| Throttle excedido | 429 |

No hay personalización global de excepciones y no existen pruebas Feature de estos casos; el formato exacto no está protegido contra regresiones.

## Scribe y seguridad del contrato

`config/scribe.php` tiene autenticación global deshabilitada. Los YAML marcan correctamente logout y recursos hijos, pero las acciones documentadas de vehículos aparecen no autenticadas, y ocho acciones protegidas ni siquiera están presentes. No deben usarse esos flags para tomar decisiones de acceso; la fuente efectiva es `routes/api.php`.

## Checklist de cambio seguro

- Mantener la ruta dentro de `auth:sanctum` salvo decisión explícita de hacerla pública.
- Para listados, acotar la consulta; `viewAny()` por sí solo no filtra.
- Para instancias, mantener binding + policy y probar usuario cruzado.
- Para FKs de vehículo, validar pertenencia además de `exists`.
- No aceptar `user_id` del cliente para asignar ownership.
- Revisar campos expuestos por Resource y relaciones cargadas.
- Para archivos privados, no usar una URL pública como sustituto de autorización.
- Añadir pruebas 401, 403, 404/ownership, 422 y éxito.
- Actualizar Scribe sin incluir tokens reales.

Los hallazgos accionables están en [Riesgos conocidos](11-known-risks.md).

Última verificación: 2026-08-21.

Fuentes consultadas: rutas, `AuthController`, Requests, Resources, modelos, policies, `bootstrap/app.php`, `config/auth.php`, `config/sanctum.php`, `config/filesystems.php`, `config/scribe.php`, `.env.example` y migración de tokens.
