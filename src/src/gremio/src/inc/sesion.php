<?php
/**
 * =====================================================================
 *  inc/sesion.php · Sesión y cookies del Gremio · PLANTILLA DEL ALUMNO
 * =====================================================================
 *  PARTE 3 (UD5) · CE 4.b (sesiones) · CE 4.c (cookies) · CE 4.e.1 (autenticación)
 *
 *  Recuerda la amnesia de la UD2: PHP olvida todo al terminar la petición.
 *  La SESIÓN guarda datos en el SERVIDOR; el navegador solo guarda una
 *  cookie con el identificador (PHPSESSID). Las COOKIES propias guardan
 *  datos pequeños en el NAVEGADOR (aquí, preferencias no sensibles).
 */

declare(strict_types=1);

const COOKIE_TEMA    = 'gremio_tema';
const COOKIE_USUARIO = 'gremio_usuario';
const TEMAS          = ['claro', 'oscuro'];

/** Arranca la sesión con una cookie de sesión segura. (Dado) */
function iniciarSesion(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/** Devuelve el aventurero guardado en la sesión, o null. (Dado) */
function aventureroActual(): ?array
{
    return $_SESSION['aventurero'] ?? null;
}

/**
 * Protege una página: si no hay sesión, redirige a login.php?motivo=privado
 * y TERMINA el script. Si la hay, devuelve el aventurero.
 *
 * TODO (Parte 3): ahora devuelve un invitado para que puedas hacer las
 * partes 1 y 2 sin login. Cuando llegues a la Parte 3, cámbialo.
 */
function requiereLogin(): array
{
    return ['id' => 0, 'usuario' => 'invitado', 'nombre' => 'Invitado', 'clase' => '—']; // TODO
}

/** Guarda un mensaje para mostrarlo UNA vez en la siguiente página. (Dado) */
function mensajeFlash(string $texto): void
{
    $_SESSION['flash'] = $texto;
}

/** Recupera y borra el mensaje flash. (Dado) */
function leerFlash(): ?string
{
    $texto = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $texto;
}

/**
 * Tema guardado en la cookie COOKIE_TEMA. Si no existe o su valor no está
 * en TEMAS (¡alguien puede haberla manipulado!), devuelve 'claro'.
 */
function temaActual(): string
{
    return 'claro'; // TODO
}

/**
 * Guarda el tema en la cookie COOKIE_TEMA durante un año.
 * TODO: setcookie con array de opciones: expires, path '/', httponly, samesite 'Lax'.
 */
function guardarTema(string $tema): void
{
    // TODO
}

/** Guarda el NOMBRE DE USUARIO (nunca la contraseña) 30 días en COOKIE_USUARIO. */
function recordarUsuario(string $usuario): void
{
    // TODO
}

/** Borra la cookie COOKIE_USUARIO (pista: caducidad en el pasado). */
function olvidarUsuario(): void
{
    // TODO
}

/**
 * Comprueba las credenciales contra la tabla `aventureros`.
 * Devuelve id, usuario, nombre y clase (SIN el hash) o null si no son correctas.
 *
 * TODO:
 *  1. Consulta preparada con un marcador :usuario. PROHIBIDO concatenar.
 *  2. Si no hay fila → null.
 *  3. password_verify($clave, $fila['password_hash']); si falla → null.
 *  4. Quita el hash del array antes de devolverlo.
 *  Pregunta para la memoria: ¿por qué el mensaje de error es el mismo si
 *  falla el usuario que si falla la contraseña?
 */
function autenticar(PDO $pdo, string $usuario, string $clave): ?array
{
    return null; // TODO
}

/**
 * Cierra la sesión POR COMPLETO: vacía $_SESSION, caduca la cookie de sesión
 * (session_name(), session_get_cookie_params()) y llama a session_destroy().
 */
function cerrarSesion(): void
{
    // TODO
}
