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

## SEC-004 — Contratos de respuesta Scribe incompletos

- Evidencia: la generación cubre las 23 acciones y representa correctamente 21 protegidas y 2 públicas, pero sus bloques `responses` y `responseFields` están vacíos; Scribe también advierte que los Form Requests no implementan `bodyParameters()`.
- Impacto: OpenAPI, Postman y la documentación HTML no permiten a consumidores comprobar con precisión los esquemas de éxito y error.
- Severidad: media como riesgo de integración/documentación.
- Estado: parcialmente resuelto el 2026-08-24: cobertura y autenticación fueron corregidas; faltan ejemplos y esquemas explícitos y seguros.

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

## TEST-002 — SQLite no cubre diferencias del motor MySQL

- Evidencia: `phpunit.xml` usa SQLite `:memory:`, mientras `.env.example` usa MySQL y el esquema contiene `year`, decimales, FKs y unicidad dependiente de collation.
- Impacto: la suite puede pasar y fallar en MySQL por tipos, constraints, SQL o comparación de strings.
- Severidad: media para cambios de persistencia.
- Estado: abierto; mantener rapidez con SQLite y añadir verificación MySQL en CI/entorno desechable para cambios sensibles.

## TEST-003 — La suite no configura una clave de aplicación

- Evidencia: `composer test` ejecuta el ejemplo Unit, pero el ejemplo Feature falla con `MissingAppKeyException`; `phpunit.xml` no define `APP_KEY`.
- Impacto: la línea base de pruebas está roja antes de añadir cobertura del dominio y puede ocultar regresiones posteriores.
- Severidad: media.
- Estado: abierto; definir una clave exclusiva de testing, sin reutilizar secretos, y comprobar la suite completa.

## CODE-001 — Pint reporta deuda de formato existente

- Evidencia: `./vendor/bin/pint --test` reporta cambios requeridos en 27 archivos PHP.
- Impacto: una comprobación de estilo global no puede actuar como puerta de CI hasta acordar o aplicar una línea base.
- Severidad: baja.
- Estado: abierto; resolver en un cambio mecánico separado para no mezclar formato con comportamiento.

## OPS-001 — Health check no valida dependencias

- Evidencia: `bootstrap/app.php` registra el health estándar en `/up` sin checks propios.
- Impacto: puede responder 200 con DB, Storage o workers indisponibles.
- Severidad: media para readiness/monitoreo.
- Estado: abierto; separar liveness y readiness según infraestructura real.

## OPS-002 — Sin telemetría específica del dominio

- Evidencia: no hay llamadas propias de Log, métricas, tracing, auditoría ni reporter de excepciones; `withExceptions` está vacío.
- Impacto: investigar fallos de negocio, abuso o regresiones depende de logs genéricos y reproducción manual.
- Severidad: media.
- Estado: abierto; definir eventos auditables, correlación, métricas y alertas sin registrar datos sensibles.

## OPS-003 — Canal single sin rotación como default local

- Evidencia: `.env.example` selecciona `stack` con `single`; `single` escribe un archivo continuo.
- Impacto: si se hereda fuera de local, el log puede crecer sin límite y agotar disco.
- Severidad: condicionada al despliegue.
- Estado: pendiente de infraestructura; usar rotación externa, `daily` o logging centralizado.

## Mantenimiento del registro

Cada entrada nueva debe incluir evidencia reproducible, impacto, severidad contextual y estado. Al corregirla, enlaza pruebas de regresión y conserva una nota breve de resolución o archívala según la política que se defina en el paso 10. No registres posibilidades genéricas sin evidencia en este repositorio.

Última verificación: 2026-08-24.

Fuentes consultadas: `routes/api.php`, `bootstrap/app.php`, configuración Sanctum/filesystem/Scribe, AuthController, Requests, Resources, policies, migraciones y tests.
