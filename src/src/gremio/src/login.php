<?php
/**
 * =====================================================================
 *  login.php · Inicio de sesión contra MySQL · PLANTILLA DEL ALUMNO
 * =====================================================================
 *  PARTE 3 · CE 4.e.1 (autenticación) · CE 4.b (sesión) · CE 4.c (cookie)
 */

declare(strict_types=1);

require_once __DIR__ . '/inc/funciones.php';
require_once __DIR__ . '/inc/sesion.php';
require_once __DIR__ . '/inc/conexion.php';

iniciarSesion();

if (aventureroActual() !== null) {
    header('Location: tablon.php');
    exit;
}

$error    = null;
$usuario  = '';     // TODO: recupéralo de la cookie COOKIE_USUARIO si existe
$recordar = false;  // TODO: marcado si había usuario recordado

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO (Parte 3):
    //  1. Lee usuario (con trim), clave y la casilla "recordar".
    //  2. Si falta alguno → $error = 'Escribe tu usuario y tu contraseña.'
    //  3. Llama a autenticar(conectar(), $usuario, $clave) dentro de try/catch
    //     (PDOException): el detalle técnico a error_log(), al usuario un
    //     mensaje genérico. NUNCA muestres $ex->getMessage() en pantalla.
    //  4. Si es correcto:
    //       session_regenerate_id(true);       ← ¿por qué? (a la memoria)
    //       guarda el aventurero en $_SESSION['aventurero'],
    //       inicializa $_SESSION['zurron'] y guarda la hora en $_SESSION['entrada'],
    //       recordarUsuario() u olvidarUsuario() según la casilla,
    //       mensajeFlash de bienvenida y redirige a tablon.php.
    //  5. Si no → $error = 'Usuario o contraseña incorrectos.'
}

$motivo = match ($_GET['motivo'] ?? '') {
    'privado' => 'Esa sala es solo para miembros del gremio. Inicia sesión.',
    'salida'  => 'Has salido del gremio. Tu zurrón se ha vaciado.',
    default   => null,
};

$titulo = 'Entrar';
$aventurero = null;
require __DIR__ . '/inc/cabecera.php';
?>
<section class="tarjeta estrecha">
    <h1>Entrada al gremio</h1>
    <?php if ($motivo !== null): ?><p class="aviso"><?= e($motivo) ?></p><?php endif; ?>
    <?php if ($error !== null): ?><p class="error"><?= e($error) ?></p><?php endif; ?>

    <form method="post" action="login.php">
        <label>Usuario
            <input type="text" name="usuario" value="<?= e($usuario) ?>" maxlength="30" required autofocus>
        </label>
        <label>Contraseña
            <input type="password" name="clave" required>
        </label>
        <label class="casilla">
            <input type="checkbox" name="recordar" <?= $recordar ? 'checked' : '' ?>>
            Recordar mi usuario en este navegador
        </label>
        <button type="submit">Entrar</button>
    </form>
</section>
<?php require __DIR__ . '/inc/pie.php'; ?>
