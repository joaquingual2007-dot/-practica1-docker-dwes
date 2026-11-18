<?php
/**
 * =====================================================================
 *  zurron.php · Mi zurrón: misiones aceptadas · PLANTILLA DEL ALUMNO
 * =====================================================================
 *  PARTE 2 (listar y total) · PARTE 3 (los datos viven en $_SESSION)
 */

declare(strict_types=1);

require_once __DIR__ . '/inc/funciones.php';
require_once __DIR__ . '/inc/sesion.php';

iniciarSesion();
$aventurero = requiereLogin();
$misiones   = require __DIR__ . '/inc/datos_misiones.php';
$_SESSION['zurron'] ??= [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) && is_string($_POST['id']) ? trim($_POST['id']) : '';
    $indice = array_search($id, $_SESSION['zurron'], true);

    if ($indice !== false) {
        unset($_SESSION['zurron'][$indice]);
        $_SESSION['zurron'] = array_values($_SESSION['zurron']);

        $mision = buscarMision($misiones, $id);
        $tituloMision = $mision !== null ? $mision['titulo'] : $id;
        mensajeFlash("Has abandonado la misión «{$tituloMision}».");
    } else {
        mensajeFlash('Esa misión no está en tu zurrón.');
    }

    header('Location: zurron.php');
    exit;
}

$ids   = $_SESSION['zurron'];
$total = totalRecompensa($misiones, $ids);
$flash = leerFlash();

$titulo = 'Mi zurrón';
require __DIR__ . '/inc/cabecera.php';
?>
<h1>Mi zurrón</h1>
<?php if ($flash !== null): ?><p class="aviso"><?= e($flash) ?></p><?php endif; ?>

<?php if (empty($ids)): ?>
    <p>Tu zurrón está vacío. Visita el <a href="tablon.php">tablón de misiones</a> para aceptar encargos.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Misión</th>
                <th>Rango</th>
                <th>Recompensa</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ids as $idMision): ?>
                <?php 
                $m = buscarMision($misiones, $idMision); 
                if ($m === null) continue;
                ?>
                <tr>
                    <td>
                        <strong><?= e($m['titulo']) ?></strong><br>
                        <span class="nota"><?= e($m['zona']) ?></span>
                    </td>
                    <td>
                        <span class="rango"><?= e($m['rango']) ?> · <?= e(etiquetaRango($m['rango'])) ?></span>
                    </td>
                    <td class="oro"><?= e(formatearOro($m['recompensa'])) ?></td>
                    <td>
                        <form method="post" action="zurron.php">
                            <input type="hidden" name="id" value="<?= e($m['id']) ?>">
                            <button type="submit" class="secundario">Abandonar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total recompensa acumulada</th>
                <th class="oro"><?= e(formatearOro($total)) ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
<?php endif; ?>

<p class="nota">El zurrón vive en la sesión: si sales del gremio, se vacía.</p>
<?php require __DIR__ . '/inc/pie.php'; ?>
