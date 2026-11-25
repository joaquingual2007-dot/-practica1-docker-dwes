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
        'httponly'  => true,
        'samesite'  => 'Lax',
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
 */
function requiereLogin(): array
{
    $aventurero = aventureroActual();

    if ($aventurero === null) {
        header('Location: login.php?motivo=privado');
        exit;
    }

    return $aventurero;
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
    $tema = $_COOKIE[COOKIE_TEMA] ?? '';

    if (in_array($tema, TEMAS, true)) {
        return $tema;
    }

    return 'claro';
}

/**
 * Guarda el tema en la cookie COOKIE_TEMA durante un año.
 */
function guardarTema(string $tema): void
{
    setcookie(COOKIE_TEMA, $tema, [
        'expires'  => time() + 365 * 24 * 60 * 60,
        'path'     => '/',
        'httponly'  => false,
        'samesite'  => 'Lax',
    ]);
}

/** Guarda el NOMBRE DE USUARIO (nunca la contraseña) 30 días en COOKIE_USUARIO. */
function recordarUsuario(string $usuario): void
{
    setcookie(COOKIE_USUARIO, $usuario, [
        'expires'  => time() + 30 * 24 * 60 * 60,
        'path'     => '/',
        'httponly'  => true,
        'samesite'  => 'Lax',
    ]);
}

/** Borra la cookie COOKIE_USUARIO (pista: caducidad en el pasado). */
function olvidarUsuario(): void
{
    setcookie(COOKIE_USUARIO, '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'httponly'  => true,
        'samesite'  => 'Lax',
    ]);
}

/**
 * Comprueba las credenciales contra la tabla `aventureros`.
 * Devuelve id, usuario, nombre y clase (SIN el hash) o null si no son correctas.
 */
function autenticar(PDO $pdo, string $usuario, string $clave): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM aventureros WHERE usuario = :usuario');
    $stmt->execute([':usuario' => $usuario]);
    $fila = $stmt->fetch();

    if ($fila === false) {
        return null;
    }

    if (!password_verify($clave, $fila['password_hash'])) {
        return null;
    }

    unset($fila['password_hash']);
    return $fila;
}

/**
 * Cierra la sesión POR COMPLETO: vacía $_SESSION, caduca la cookie de sesión
 * (session_name(), session_get_cookie_params()) y llama a session_destroy().
 */
function cerrarSesion(): void
{
    $_SESSION = [];

    $params = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires'  => time() - 3600,
        'path'     => $params['path'],
        'domain'   => $params['domain'],
        'secure'   => $params['secure'],
        'httponly'  => $params['httponly'],
        'samesite'  => $params['samesite'] ?? 'Lax',
    ]);

    session_destroy();
}
