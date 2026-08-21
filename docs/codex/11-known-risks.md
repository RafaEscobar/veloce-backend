# Riesgos conocidos

Este registro contiene hallazgos confirmados en código/configuración. “Confirmado” significa que la condición existe; el impacto puede depender del modelo de amenazas o del despliegue. No implica que haya sido explotada.

## SEC-001 — Fotos de vehículos públicamente accesibles

- Evidencia: `VehicleController` guarda en el disco `public`; `config/filesystems.php` lo publica bajo `/storage`; `VehicleResource` devuelve URL absoluta.
- Impacto: la descarga no pasa por Sanctum ni policy. Quien conozca o reciba la URL puede acceder a una foto aunque no sea propietario.
- Severidad: media si las fotos son privadas; baja si el producto decide que son públicas.
- Estado: abierto; requiere decisión de producto. Si deben ser privadas, usar almacenamiento privado y endpoint autorizado o URL temporal.

## SEC-002 — Tokens sin expiración y con ability global

- Evidencia: registro/login llaman `createToken('auth_token')` sin abilities ni expiración; Sanctum configura `expiration => null`.
- Impacto: cada login crea un token `*` que sigue válido hasta revocación. Un token filtrado conserva acceso indefinido si el usuario no ejecuta logout/revocación.
- Severidad: media.
- Estado: abierto; definir duración, abilities, rotación y gestión de sesiones según requisitos.

## SEC-003 — Confianza en cualquier proxy

- Evidencia: `bootstrap/app.php` configura `trustProxies(at: '*')` y confía en forwarded IP/host/port/proto.
- Impacto: si la aplicación acepta tráfico directo o el proxy frontal no limpia headers, un cliente podría influir en IP, host o esquema percibidos, afectando URLs, auditoría y controles dependientes de IP.
- Severidad: condicionada al despliegue; potencialmente media.
- Estado: pendiente de validar infraestructura. Restringir proxies o garantizar aislamiento/limpieza de headers.

## SEC-004 — Documentación Scribe contradice autenticación efectiva

- Evidencia: `auth.enabled` es `false`; store/update/destroy de vehículos figuran `authenticated: false`; faltan ocho rutas protegidas.
- Impacto: consumidores pueden implementar llamadas inseguras o asumir que rutas protegidas son públicas; OpenAPI/Postman heredarán un contrato incompleto.
- Severidad: media como riesgo de integración/documentación.
- Estado: abierto; corregir configuración/anotaciones y regenerar tras restaurar dependencias.

## SEC-005 — Diferencia de respuestas revela existencia de vehicle_id

- Evidencia: Requests usan `exists:vehicles,id` antes de `user()->vehicles()->findOrFail()`. ID inexistente produce 422; ID existente de otra cuenta avanza y produce 404.
- Impacto: un usuario autenticado puede distinguir IDs inexistentes de vehículos pertenecientes a otra cuenta.
- Severidad: baja; depende de sensibilidad de IDs y volumen permitido.
- Estado: abierto; decidir si se unifica validación/ownership y respuesta. Cubrir con pruebas antes de cambiar semántica.

## SEC-006 — Rutas protegidas sin rate limit explícito

- Evidencia: solo register/login declaran `throttle`; no hay RateLimiter propio en provider ni throttle explícito en el grupo Sanctum.
- Impacto: usuarios autenticados pueden generar carga elevada o abuso de escritura/lectura sin límite de aplicación específico.
- Severidad: baja a media según controles del gateway y capacidad.
- Estado: pendiente de validar límites externos y definir cuotas de API.

## TEST-001 — Controles de ownership sin pruebas de regresión

- Evidencia: `tests/Feature` y `tests/Unit` contienen solo ejemplos; no prueban 401, acceso cruzado, cambio de `vehicle_id` ni exposición de archivos.
- Impacto: una refactorización puede retirar un scope, policy o comprobación sin señal automatizada.
- Severidad: alta por amplitud del control afectado.
- Estado: abierto; priorizar matriz Feature de seguridad en el paso 7.

## DATA-001 — Fotos huérfanas por cascadas o fallos parciales

- Evidencia: limpieza de foto solo en métodos update/destroy del controlador; FKs pueden borrar vehículos por cascada y no hay transacción entre Storage y DB.
- Impacto: archivos públicos sin fila asociada, consumo de almacenamiento o estados inconsistentes durante reemplazo.
- Severidad: baja a media.
- Estado: abierto; definir lifecycle transaccional/compensatorio y limpieza periódica.

## Mantenimiento del registro

Cada entrada nueva debe incluir evidencia reproducible, impacto, severidad contextual y estado. Al corregirla, enlaza pruebas de regresión y conserva una nota breve de resolución o archívala según la política que se defina en el paso 10. No registres posibilidades genéricas sin evidencia en este repositorio.

Última verificación: 2026-08-21.

Fuentes consultadas: `routes/api.php`, `bootstrap/app.php`, configuración Sanctum/filesystem/Scribe, AuthController, Requests, Resources, policies, migraciones y tests.
