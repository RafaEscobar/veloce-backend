<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.10.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.10.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduccion" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduccion">
                    <a href="#introduccion">Introducción</a>
                </li>
                            </ul>
                    <ul id="tocify-header-autenticacion" class="tocify-header">
                <li class="tocify-item level-1" data-unique="autenticacion">
                    <a href="#autenticacion">Autenticación</a>
                </li>
                                    <ul id="tocify-subheader-autenticacion" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="autenticacion-POSTapi-register">
                                <a href="#autenticacion-POSTapi-register">Registra un nuevo usuario en el sistema.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autenticacion-POSTapi-login">
                                <a href="#autenticacion-POSTapi-login">Inicia sesión para un usuario existente en la aplicación.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autenticacion-POSTapi-logout">
                                <a href="#autenticacion-POSTapi-logout">Cierra la sesión del usuario autenticado actualmente.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-cargas-de-gasolina" class="tocify-header">
                <li class="tocify-item level-1" data-unique="cargas-de-gasolina">
                    <a href="#cargas-de-gasolina">Cargas de gasolina</a>
                </li>
                                    <ul id="tocify-subheader-cargas-de-gasolina" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="cargas-de-gasolina-POSTapi-gasoline-refills">
                                <a href="#cargas-de-gasolina-POSTapi-gasoline-refills">Almacena una nueva recarga de gasolina.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cargas-de-gasolina-PUTapi-gasoline-refills--id-">
                                <a href="#cargas-de-gasolina-PUTapi-gasoline-refills--id-">Actualiza una recarga existente.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cargas-de-gasolina-DELETEapi-gasoline-refills--id-">
                                <a href="#cargas-de-gasolina-DELETEapi-gasoline-refills--id-">Elimina una recarga.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-mantenimientos" class="tocify-header">
                <li class="tocify-item level-1" data-unique="mantenimientos">
                    <a href="#mantenimientos">Mantenimientos</a>
                </li>
                                    <ul id="tocify-subheader-mantenimientos" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="mantenimientos-POSTapi-maintenances">
                                <a href="#mantenimientos-POSTapi-maintenances">Almacena un nuevo mantenimiento.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="mantenimientos-PUTapi-maintenances--id-">
                                <a href="#mantenimientos-PUTapi-maintenances--id-">Actualiza un mantenimiento existente.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="mantenimientos-DELETEapi-maintenances--id-">
                                <a href="#mantenimientos-DELETEapi-maintenances--id-">Elimina un mantenimiento.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-recordatorios" class="tocify-header">
                <li class="tocify-item level-1" data-unique="recordatorios">
                    <a href="#recordatorios">Recordatorios</a>
                </li>
                                    <ul id="tocify-subheader-recordatorios" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="recordatorios-POSTapi-reminders">
                                <a href="#recordatorios-POSTapi-reminders">Almacena un nuevo recordatorio.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="recordatorios-PUTapi-reminders--id-">
                                <a href="#recordatorios-PUTapi-reminders--id-">Actualiza un recordatorio existente.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="recordatorios-DELETEapi-reminders--id-">
                                <a href="#recordatorios-DELETEapi-reminders--id-">Elimina un recordatorio.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-vehiculos" class="tocify-header">
                <li class="tocify-item level-1" data-unique="vehiculos">
                    <a href="#vehiculos">Vehículos</a>
                </li>
                                    <ul id="tocify-subheader-vehiculos" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="vehiculos-POSTapi-vehicles">
                                <a href="#vehiculos-POSTapi-vehicles">Crea un nuevo vehículo.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehiculos-PUTapi-vehicles--id-">
                                <a href="#vehiculos-PUTapi-vehicles--id-">Actualiza un vehículo existente.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehiculos-DELETEapi-vehicles--id-">
                                <a href="#vehiculos-DELETEapi-vehicles--id-">Elimina un vehículo.</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: May 22, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduccion">Introducción</h1>
<p>Bienvenido a la documentación de la API de <strong>Veloce</strong>, una plataforma integral diseñada para la gestión, el control y el seguimiento del mantenimiento de vehículos.</p>
<p>A través de esta API, los usuarios autenticados pueden administrar de forma detallada sus vehículos y llevar el registro de:</p>
<ul>
<li><strong>Gestión de Vehículos</strong>: Registro de automóviles, motocicletas y otros medios de transporte con sus respectivos estados y tipos.</li>
<li><strong>Control de Mantenimientos</strong>: Historial detallado de servicios preventivos y correctivos, incluyendo costos asociados y notas de taller.</li>
<li><strong>Cargas de Gasolina</strong>: Registro y monitorización del consumo de combustible y gastos relacionados.</li>
<li><strong>Problemas Pendientes</strong>: Reporte, seguimiento de prioridad y estado de fallos o averías identificadas en los vehículos.</li>
<li><strong>Recordatorios</strong>: Programación de alertas para tareas o eventos periódicos (como la renovación de seguros, verificación, etc.).</li>
</ul>
<aside>
    <strong>URL Base</strong>: <code>http://localhost:8000</code>
</aside>
<p>Esta documentación está diseñada para proporcionar todos los detalles técnicos necesarios para realizar integraciones y consumir los endpoints de la API de forma efectiva.</p>
<aside>A medida que te desplaces por la página, verás ejemplos de solicitudes y respuestas en diferentes lenguajes de programación en el panel lateral derecho (o dentro del flujo del contenido en pantallas móviles). Puedes alternar entre lenguajes usando las pestañas de la esquina superior derecha.</aside>

        

        <h1 id="autenticacion">Autenticación</h1>

    

                                <h2 id="autenticacion-POSTapi-register">Registra un nuevo usuario en el sistema.</h2>

<p>
</p>

<p>Crea un nuevo registro de usuario en la base de datos, encripta su contraseña
y genera un token de acceso personal (PAT) para el nuevo usuario.</p>

<span id="example-requests-POSTapi-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Alejandro\",
    \"last_name\": \"Ramirez\",
    \"email\": \"alejandro1@gmail.com\",
    \"password\": \"secret123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Alejandro",
    "last_name": "Ramirez",
    "email": "alejandro1@gmail.com",
    "password": "secret123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-register">
</span>
<span id="execution-results-POSTapi-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-register" data-method="POST"
      data-path="api/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-register"
                    onclick="tryItOut('POSTapi-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-register"
                    onclick="cancelTryOut('POSTapi-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-register"
               value="Alejandro"
               data-component="body">
    <br>
<p>Nombre del usuario. Example: <code>Alejandro</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="last_name"                data-endpoint="POSTapi-register"
               value="Ramirez"
               data-component="body">
    <br>
<p>Apellidos del usuario. Example: <code>Ramirez</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-register"
               value="alejandro1@gmail.com"
               data-component="body">
    <br>
<p>Correo electrónico del usuario. Example: <code>alejandro1@gmail.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-register"
               value="secret123"
               data-component="body">
    <br>
<p>Contraseña del usuario. Example: <code>secret123</code></p>
        </div>
        </form>

                    <h2 id="autenticacion-POSTapi-login">Inicia sesión para un usuario existente en la aplicación.</h2>

<p>
</p>

<p>Verifica las credenciales de acceso (email y contraseña). Si son válidas,
autentica al usuario y genera un nuevo token de acceso. Si las credenciales
son incorrectas, lanza una excepción de validación.</p>

<span id="example-requests-POSTapi-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"alejandro@example.com\",
    \"password\": \"secret123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "alejandro@example.com",
    "password": "secret123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-login">
</span>
<span id="execution-results-POSTapi-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-login" data-method="POST"
      data-path="api/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-login"
                    onclick="tryItOut('POSTapi-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-login"
                    onclick="cancelTryOut('POSTapi-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-login"
               value="alejandro@example.com"
               data-component="body">
    <br>
<p>Correo electrónico del usuario. Example: <code>alejandro@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-login"
               value="secret123"
               data-component="body">
    <br>
<p>Contraseña del usuario. Example: <code>secret123</code></p>
        </div>
        </form>

                    <h2 id="autenticacion-POSTapi-logout">Cierra la sesión del usuario autenticado actualmente.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/logout" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/logout"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-logout">
</span>
<span id="execution-results-POSTapi-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-logout" data-method="POST"
      data-path="api/logout"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-logout"
                    onclick="tryItOut('POSTapi-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-logout"
                    onclick="cancelTryOut('POSTapi-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="cargas-de-gasolina">Cargas de gasolina</h1>

    

                                <h2 id="cargas-de-gasolina-POSTapi-gasoline-refills">Almacena una nueva recarga de gasolina.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-gasoline-refills">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/gasoline-refills" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"vehicle_id\": 1,
    \"amount\": 45.5,
    \"liters\": 25.4,
    \"date\": \"2026-05-19\",
    \"gas_station\": \"Repsol Centro\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/gasoline-refills"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "vehicle_id": 1,
    "amount": 45.5,
    "liters": 25.4,
    "date": "2026-05-19",
    "gas_station": "Repsol Centro"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-gasoline-refills">
</span>
<span id="execution-results-POSTapi-gasoline-refills" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-gasoline-refills"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-gasoline-refills"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-gasoline-refills" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-gasoline-refills">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-gasoline-refills" data-method="POST"
      data-path="api/gasoline-refills"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-gasoline-refills', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-gasoline-refills"
                    onclick="tryItOut('POSTapi-gasoline-refills');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-gasoline-refills"
                    onclick="cancelTryOut('POSTapi-gasoline-refills');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-gasoline-refills"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/gasoline-refills</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-gasoline-refills"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-gasoline-refills"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="POSTapi-gasoline-refills"
               value="1"
               data-component="body">
    <br>
<p>ID del vehículo al que pertenece la recarga. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="amount"                data-endpoint="POSTapi-gasoline-refills"
               value="45.5"
               data-component="body">
    <br>
<p>Monto total gastado en la recarga. Example: <code>45.5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>liters</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="liters"                data-endpoint="POSTapi-gasoline-refills"
               value="25.4"
               data-component="body">
    <br>
<p>Cantidad de litros de gasolina recargados. Example: <code>25.4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="date"                data-endpoint="POSTapi-gasoline-refills"
               value="2026-05-19"
               data-component="body">
    <br>
<p>Fecha de la recarga en formato AAAA-MM-DD. Example: <code>2026-05-19</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gas_station</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="gas_station"                data-endpoint="POSTapi-gasoline-refills"
               value="Repsol Centro"
               data-component="body">
    <br>
<p>Nombre o ubicación de la gasolinera. Example: <code>Repsol Centro</code></p>
        </div>
        </form>

                    <h2 id="cargas-de-gasolina-PUTapi-gasoline-refills--id-">Actualiza una recarga existente.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-gasoline-refills--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/gasoline-refills/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"amount\": 50,
    \"liters\": 28,
    \"date\": \"2026-05-19\",
    \"gas_station\": \"Repsol Centro\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/gasoline-refills/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "amount": 50,
    "liters": 28,
    "date": "2026-05-19",
    "gas_station": "Repsol Centro"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-gasoline-refills--id-">
</span>
<span id="execution-results-PUTapi-gasoline-refills--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-gasoline-refills--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-gasoline-refills--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-gasoline-refills--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-gasoline-refills--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-gasoline-refills--id-" data-method="PUT"
      data-path="api/gasoline-refills/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-gasoline-refills--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-gasoline-refills--id-"
                    onclick="tryItOut('PUTapi-gasoline-refills--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-gasoline-refills--id-"
                    onclick="cancelTryOut('PUTapi-gasoline-refills--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-gasoline-refills--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/gasoline-refills/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/gasoline-refills/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-gasoline-refills--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-gasoline-refills--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-gasoline-refills--id-"
               value="1"
               data-component="url">
    <br>
<p>ID del registro de gasolina. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="amount"                data-endpoint="PUTapi-gasoline-refills--id-"
               value="50"
               data-component="body">
    <br>
<p>Monto total gastado en la recarga. Example: <code>50</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>liters</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="liters"                data-endpoint="PUTapi-gasoline-refills--id-"
               value="28"
               data-component="body">
    <br>
<p>Cantidad de litros de gasolina recargados. Example: <code>28</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="date"                data-endpoint="PUTapi-gasoline-refills--id-"
               value="2026-05-19"
               data-component="body">
    <br>
<p>Fecha de la recarga en formato AAAA-MM-DD. Example: <code>2026-05-19</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gas_station</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="gas_station"                data-endpoint="PUTapi-gasoline-refills--id-"
               value="Repsol Centro"
               data-component="body">
    <br>
<p>Nombre o ubicación de la gasolinera. Example: <code>Repsol Centro</code></p>
        </div>
        </form>

                    <h2 id="cargas-de-gasolina-DELETEapi-gasoline-refills--id-">Elimina una recarga.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-gasoline-refills--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/gasoline-refills/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/gasoline-refills/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-gasoline-refills--id-">
</span>
<span id="execution-results-DELETEapi-gasoline-refills--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-gasoline-refills--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-gasoline-refills--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-gasoline-refills--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-gasoline-refills--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-gasoline-refills--id-" data-method="DELETE"
      data-path="api/gasoline-refills/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-gasoline-refills--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-gasoline-refills--id-"
                    onclick="tryItOut('DELETEapi-gasoline-refills--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-gasoline-refills--id-"
                    onclick="cancelTryOut('DELETEapi-gasoline-refills--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-gasoline-refills--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/gasoline-refills/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-gasoline-refills--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-gasoline-refills--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-gasoline-refills--id-"
               value="1"
               data-component="url">
    <br>
<p>ID del registro de gasolina. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="mantenimientos">Mantenimientos</h1>

    

                                <h2 id="mantenimientos-POSTapi-maintenances">Almacena un nuevo mantenimiento.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-maintenances">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/maintenances" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"vehicle_id\": 1,
    \"name\": \"Cambio de aceite y filtro\",
    \"date\": \"2026-05-19\",
    \"cost\": 120.5,
    \"is_reminder_enabled\": true,
    \"notes\": \"Se usó aceite sintético 5W-30.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/maintenances"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "vehicle_id": 1,
    "name": "Cambio de aceite y filtro",
    "date": "2026-05-19",
    "cost": 120.5,
    "is_reminder_enabled": true,
    "notes": "Se usó aceite sintético 5W-30."
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-maintenances">
</span>
<span id="execution-results-POSTapi-maintenances" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-maintenances"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-maintenances"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-maintenances" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-maintenances">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-maintenances" data-method="POST"
      data-path="api/maintenances"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-maintenances', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-maintenances"
                    onclick="tryItOut('POSTapi-maintenances');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-maintenances"
                    onclick="cancelTryOut('POSTapi-maintenances');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-maintenances"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/maintenances</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-maintenances"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-maintenances"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="POSTapi-maintenances"
               value="1"
               data-component="body">
    <br>
<p>ID del vehículo al que pertenece el mantenimiento. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-maintenances"
               value="Cambio de aceite y filtro"
               data-component="body">
    <br>
<p>Nombre o tipo de mantenimiento. Example: <code>Cambio de aceite y filtro</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="date"                data-endpoint="POSTapi-maintenances"
               value="2026-05-19"
               data-component="body">
    <br>
<p>Fecha del mantenimiento en formato AAAA-MM-DD. Example: <code>2026-05-19</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>cost</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="cost"                data-endpoint="POSTapi-maintenances"
               value="120.5"
               data-component="body">
    <br>
<p>Monto total del costo del mantenimiento. Example: <code>120.5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_reminder_enabled</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-maintenances" style="display: none">
            <input type="radio" name="is_reminder_enabled"
                   value="true"
                   data-endpoint="POSTapi-maintenances"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-maintenances" style="display: none">
            <input type="radio" name="is_reminder_enabled"
                   value="false"
                   data-endpoint="POSTapi-maintenances"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Indica si se debe habilitar un recordatorio para este mantenimiento. Example: <code>true</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes"                data-endpoint="POSTapi-maintenances"
               value="Se usó aceite sintético 5W-30."
               data-component="body">
    <br>
<p>Notas adicionales sobre el mantenimiento realizado. Example: <code>Se usó aceite sintético 5W-30.</code></p>
        </div>
        </form>

                    <h2 id="mantenimientos-PUTapi-maintenances--id-">Actualiza un mantenimiento existente.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-maintenances--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/maintenances/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"vehicle_id\": 1,
    \"name\": \"Cambio de aceite y filtro\",
    \"date\": \"2026-05-19\",
    \"cost\": 130,
    \"is_reminder_enabled\": false,
    \"notes\": \"Se usó aceite sintético 5W-30.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/maintenances/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "vehicle_id": 1,
    "name": "Cambio de aceite y filtro",
    "date": "2026-05-19",
    "cost": 130,
    "is_reminder_enabled": false,
    "notes": "Se usó aceite sintético 5W-30."
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-maintenances--id-">
</span>
<span id="execution-results-PUTapi-maintenances--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-maintenances--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-maintenances--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-maintenances--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-maintenances--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-maintenances--id-" data-method="PUT"
      data-path="api/maintenances/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-maintenances--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-maintenances--id-"
                    onclick="tryItOut('PUTapi-maintenances--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-maintenances--id-"
                    onclick="cancelTryOut('PUTapi-maintenances--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-maintenances--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/maintenances/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/maintenances/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-maintenances--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-maintenances--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-maintenances--id-"
               value="1"
               data-component="url">
    <br>
<p>ID del registro de mantenimiento. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="PUTapi-maintenances--id-"
               value="1"
               data-component="body">
    <br>
<p>ID del vehículo al que pertenece el mantenimiento. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-maintenances--id-"
               value="Cambio de aceite y filtro"
               data-component="body">
    <br>
<p>Nombre o tipo de mantenimiento. Example: <code>Cambio de aceite y filtro</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="date"                data-endpoint="PUTapi-maintenances--id-"
               value="2026-05-19"
               data-component="body">
    <br>
<p>Fecha del mantenimiento en formato AAAA-MM-DD. Example: <code>2026-05-19</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>cost</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="cost"                data-endpoint="PUTapi-maintenances--id-"
               value="130"
               data-component="body">
    <br>
<p>Monto total del costo del mantenimiento. Example: <code>130</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_reminder_enabled</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-maintenances--id-" style="display: none">
            <input type="radio" name="is_reminder_enabled"
                   value="true"
                   data-endpoint="PUTapi-maintenances--id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-maintenances--id-" style="display: none">
            <input type="radio" name="is_reminder_enabled"
                   value="false"
                   data-endpoint="PUTapi-maintenances--id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Indica si se debe habilitar un recordatorio para este mantenimiento. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes"                data-endpoint="PUTapi-maintenances--id-"
               value="Se usó aceite sintético 5W-30."
               data-component="body">
    <br>
<p>Notas adicionales sobre el mantenimiento realizado. Example: <code>Se usó aceite sintético 5W-30.</code></p>
        </div>
        </form>

                    <h2 id="mantenimientos-DELETEapi-maintenances--id-">Elimina un mantenimiento.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-maintenances--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/maintenances/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/maintenances/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-maintenances--id-">
</span>
<span id="execution-results-DELETEapi-maintenances--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-maintenances--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-maintenances--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-maintenances--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-maintenances--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-maintenances--id-" data-method="DELETE"
      data-path="api/maintenances/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-maintenances--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-maintenances--id-"
                    onclick="tryItOut('DELETEapi-maintenances--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-maintenances--id-"
                    onclick="cancelTryOut('DELETEapi-maintenances--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-maintenances--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/maintenances/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-maintenances--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-maintenances--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-maintenances--id-"
               value="1"
               data-component="url">
    <br>
<p>ID del registro de mantenimiento. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="recordatorios">Recordatorios</h1>

    

                                <h2 id="recordatorios-POSTapi-reminders">Almacena un nuevo recordatorio.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-reminders">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/reminders" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"vehicle_id\": 1,
    \"name\": \"Renovación de seguro\",
    \"date\": \"2026-05-19\",
    \"description\": \"Renovar póliza anual con cobertura amplia.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/reminders"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "vehicle_id": 1,
    "name": "Renovación de seguro",
    "date": "2026-05-19",
    "description": "Renovar póliza anual con cobertura amplia."
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-reminders">
</span>
<span id="execution-results-POSTapi-reminders" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-reminders"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-reminders"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-reminders" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-reminders">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-reminders" data-method="POST"
      data-path="api/reminders"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-reminders', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-reminders"
                    onclick="tryItOut('POSTapi-reminders');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-reminders"
                    onclick="cancelTryOut('POSTapi-reminders');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-reminders"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/reminders</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-reminders"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-reminders"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="POSTapi-reminders"
               value="1"
               data-component="body">
    <br>
<p>ID del vehículo al que pertenece el recordatorio. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-reminders"
               value="Renovación de seguro"
               data-component="body">
    <br>
<p>Título o nombre corto del recordatorio. Example: <code>Renovación de seguro</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="date"                data-endpoint="POSTapi-reminders"
               value="2026-05-19"
               data-component="body">
    <br>
<p>Fecha programada para el recordatorio en formato AAAA-MM-DD. Example: <code>2026-05-19</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-reminders"
               value="Renovar póliza anual con cobertura amplia."
               data-component="body">
    <br>
<p>Descripción detallada u observaciones del recordatorio. Example: <code>Renovar póliza anual con cobertura amplia.</code></p>
        </div>
        </form>

                    <h2 id="recordatorios-PUTapi-reminders--id-">Actualiza un recordatorio existente.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-reminders--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/reminders/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"vehicle_id\": 1,
    \"name\": \"Renovación de seguro\",
    \"date\": \"2026-05-19\",
    \"description\": \"Renovar póliza anual con cobertura amplia.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/reminders/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "vehicle_id": 1,
    "name": "Renovación de seguro",
    "date": "2026-05-19",
    "description": "Renovar póliza anual con cobertura amplia."
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-reminders--id-">
</span>
<span id="execution-results-PUTapi-reminders--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-reminders--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-reminders--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-reminders--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-reminders--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-reminders--id-" data-method="PUT"
      data-path="api/reminders/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-reminders--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-reminders--id-"
                    onclick="tryItOut('PUTapi-reminders--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-reminders--id-"
                    onclick="cancelTryOut('PUTapi-reminders--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-reminders--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/reminders/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/reminders/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-reminders--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-reminders--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-reminders--id-"
               value="1"
               data-component="url">
    <br>
<p>ID del registro de recordatorio. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="PUTapi-reminders--id-"
               value="1"
               data-component="body">
    <br>
<p>ID del vehículo al que pertenece el recordatorio. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-reminders--id-"
               value="Renovación de seguro"
               data-component="body">
    <br>
<p>Título o nombre corto del recordatorio. Example: <code>Renovación de seguro</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="date"                data-endpoint="PUTapi-reminders--id-"
               value="2026-05-19"
               data-component="body">
    <br>
<p>Fecha programada para el recordatorio en formato AAAA-MM-DD. Example: <code>2026-05-19</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-reminders--id-"
               value="Renovar póliza anual con cobertura amplia."
               data-component="body">
    <br>
<p>Descripción detallada u observaciones del recordatorio. Example: <code>Renovar póliza anual con cobertura amplia.</code></p>
        </div>
        </form>

                    <h2 id="recordatorios-DELETEapi-reminders--id-">Elimina un recordatorio.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-reminders--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/reminders/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/reminders/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-reminders--id-">
</span>
<span id="execution-results-DELETEapi-reminders--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-reminders--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-reminders--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-reminders--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-reminders--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-reminders--id-" data-method="DELETE"
      data-path="api/reminders/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-reminders--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-reminders--id-"
                    onclick="tryItOut('DELETEapi-reminders--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-reminders--id-"
                    onclick="cancelTryOut('DELETEapi-reminders--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-reminders--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/reminders/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-reminders--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-reminders--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-reminders--id-"
               value="1"
               data-component="url">
    <br>
<p>ID del registro de recordatorio. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="vehiculos">Vehículos</h1>

    

                                <h2 id="vehiculos-POSTapi-vehicles">Crea un nuevo vehículo.</h2>

<p>
</p>

<p>Registra un nuevo vehículo en el sistema asociándolo automáticamente al usuario autenticado.
Si se proporciona una foto, la almacena en el disco público.</p>

<span id="example-requests-POSTapi-vehicles">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/vehicles" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "vehicle_type_id=1"\
    --form "vehicle_status_id=1"\
    --form "name=Mi Coche"\
    --form "plates=ABC-1234"\
    --form "serial_number=1HGCM82633A000000"\
    --form "gasoline_type=Magna"\
    --form "oil_type=Sintético 5W-30"\
    --form "model_name=Civic"\
    --form "model_year=2020"\
    --form "photo=@/tmp/phpZBryha" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/vehicles"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('vehicle_type_id', '1');
body.append('vehicle_status_id', '1');
body.append('name', 'Mi Coche');
body.append('plates', 'ABC-1234');
body.append('serial_number', '1HGCM82633A000000');
body.append('gasoline_type', 'Magna');
body.append('oil_type', 'Sintético 5W-30');
body.append('model_name', 'Civic');
body.append('model_year', '2020');
body.append('photo', document.querySelector('input[name="photo"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-vehicles">
</span>
<span id="execution-results-POSTapi-vehicles" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-vehicles"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-vehicles"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-vehicles" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-vehicles">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-vehicles" data-method="POST"
      data-path="api/vehicles"
      data-authed="0"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-vehicles', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-vehicles"
                    onclick="tryItOut('POSTapi-vehicles');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-vehicles"
                    onclick="cancelTryOut('POSTapi-vehicles');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-vehicles"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/vehicles</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-vehicles"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-vehicles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_type_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_type_id"                data-endpoint="POSTapi-vehicles"
               value="1"
               data-component="body">
    <br>
<p>ID del tipo de vehículo. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_status_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_status_id"                data-endpoint="POSTapi-vehicles"
               value="1"
               data-component="body">
    <br>
<p>ID del estado del vehículo. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-vehicles"
               value="Mi Coche"
               data-component="body">
    <br>
<p>Nombre o alias del vehículo. Example: <code>Mi Coche</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>plates</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="plates"                data-endpoint="POSTapi-vehicles"
               value="ABC-1234"
               data-component="body">
    <br>
<p>Placas del vehículo. Example: <code>ABC-1234</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>serial_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="serial_number"                data-endpoint="POSTapi-vehicles"
               value="1HGCM82633A000000"
               data-component="body">
    <br>
<p>Número de serie (VIN). Example: <code>1HGCM82633A000000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gasoline_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="gasoline_type"                data-endpoint="POSTapi-vehicles"
               value="Magna"
               data-component="body">
    <br>
<p>Tipo de gasolina que utiliza. Example: <code>Magna</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>oil_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="oil_type"                data-endpoint="POSTapi-vehicles"
               value="Sintético 5W-30"
               data-component="body">
    <br>
<p>Tipo de aceite que utiliza. Example: <code>Sintético 5W-30</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>model_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="model_name"                data-endpoint="POSTapi-vehicles"
               value="Civic"
               data-component="body">
    <br>
<p>Nombre del modelo del vehículo. Example: <code>Civic</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>photo</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="photo"                data-endpoint="POSTapi-vehicles"
               value=""
               data-component="body">
    <br>
<p>Foto del vehículo (imagen, máx. 2MB). Example: <code>/tmp/phpZBryha</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>model_year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="model_year"                data-endpoint="POSTapi-vehicles"
               value="2020"
               data-component="body">
    <br>
<p>Año del modelo del vehículo. Example: <code>2020</code></p>
        </div>
        </form>

                    <h2 id="vehiculos-PUTapi-vehicles--id-">Actualiza un vehículo existente.</h2>

<p>
</p>

<p>Modifica los datos de un vehículo perteneciente al usuario autenticado.
Si se sube una nueva foto, reemplaza la anterior eliminándola del almacenamiento.</p>

<span id="example-requests-PUTapi-vehicles--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/vehicles/1" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "vehicle_type_id=1"\
    --form "vehicle_status_id=1"\
    --form "name=Mi Coche Editado"\
    --form "plates=XYZ-9876"\
    --form "serial_number=1HGCM82633A000000"\
    --form "gasoline_type=Premium"\
    --form "oil_type=Sintético 10W-40"\
    --form "model_name=Accord"\
    --form "model_year=2022"\
    --form "photo=@/tmp/php5bp7zO" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/vehicles/1"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('vehicle_type_id', '1');
body.append('vehicle_status_id', '1');
body.append('name', 'Mi Coche Editado');
body.append('plates', 'XYZ-9876');
body.append('serial_number', '1HGCM82633A000000');
body.append('gasoline_type', 'Premium');
body.append('oil_type', 'Sintético 10W-40');
body.append('model_name', 'Accord');
body.append('model_year', '2022');
body.append('photo', document.querySelector('input[name="photo"]').files[0]);

fetch(url, {
    method: "PUT",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-vehicles--id-">
</span>
<span id="execution-results-PUTapi-vehicles--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-vehicles--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-vehicles--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-vehicles--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-vehicles--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-vehicles--id-" data-method="PUT"
      data-path="api/vehicles/{id}"
      data-authed="0"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-vehicles--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-vehicles--id-"
                    onclick="tryItOut('PUTapi-vehicles--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-vehicles--id-"
                    onclick="cancelTryOut('PUTapi-vehicles--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-vehicles--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/vehicles/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/vehicles/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-vehicles--id-"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-vehicles--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-vehicles--id-"
               value="1"
               data-component="url">
    <br>
<p>ID del vehículo. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_type_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_type_id"                data-endpoint="PUTapi-vehicles--id-"
               value="1"
               data-component="body">
    <br>
<p>ID del tipo de vehículo. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_status_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_status_id"                data-endpoint="PUTapi-vehicles--id-"
               value="1"
               data-component="body">
    <br>
<p>ID del estado del vehículo. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-vehicles--id-"
               value="Mi Coche Editado"
               data-component="body">
    <br>
<p>Nombre o alias del vehículo. Example: <code>Mi Coche Editado</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>plates</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="plates"                data-endpoint="PUTapi-vehicles--id-"
               value="XYZ-9876"
               data-component="body">
    <br>
<p>Placas del vehículo. Example: <code>XYZ-9876</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>serial_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="serial_number"                data-endpoint="PUTapi-vehicles--id-"
               value="1HGCM82633A000000"
               data-component="body">
    <br>
<p>Número de serie (VIN). Example: <code>1HGCM82633A000000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gasoline_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="gasoline_type"                data-endpoint="PUTapi-vehicles--id-"
               value="Premium"
               data-component="body">
    <br>
<p>Tipo de gasolina que utiliza. Example: <code>Premium</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>oil_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="oil_type"                data-endpoint="PUTapi-vehicles--id-"
               value="Sintético 10W-40"
               data-component="body">
    <br>
<p>Tipo de aceite que utiliza. Example: <code>Sintético 10W-40</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>model_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="model_name"                data-endpoint="PUTapi-vehicles--id-"
               value="Accord"
               data-component="body">
    <br>
<p>Nombre del modelo del vehículo. Example: <code>Accord</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>photo</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="photo"                data-endpoint="PUTapi-vehicles--id-"
               value=""
               data-component="body">
    <br>
<p>Nueva foto del vehículo (imagen, máx. 2MB). Example: <code>/tmp/php5bp7zO</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>model_year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="model_year"                data-endpoint="PUTapi-vehicles--id-"
               value="2022"
               data-component="body">
    <br>
<p>Año del modelo del vehículo. Example: <code>2022</code></p>
        </div>
        </form>

                    <h2 id="vehiculos-DELETEapi-vehicles--id-">Elimina un vehículo.</h2>

<p>
</p>

<p>Borra el registro del vehículo especificado. También elimina la foto
asociada del almacenamiento si existe.</p>

<span id="example-requests-DELETEapi-vehicles--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/vehicles/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/vehicles/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-vehicles--id-">
</span>
<span id="execution-results-DELETEapi-vehicles--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-vehicles--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-vehicles--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-vehicles--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-vehicles--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-vehicles--id-" data-method="DELETE"
      data-path="api/vehicles/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-vehicles--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-vehicles--id-"
                    onclick="tryItOut('DELETEapi-vehicles--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-vehicles--id-"
                    onclick="cancelTryOut('DELETEapi-vehicles--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-vehicles--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/vehicles/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-vehicles--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-vehicles--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-vehicles--id-"
               value="1"
               data-component="url">
    <br>
<p>ID del vehículo. Example: <code>1</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
