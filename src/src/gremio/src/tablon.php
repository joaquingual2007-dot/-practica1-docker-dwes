<?php
/**
 * =====================================================================
 *  tablon.php · Tablón de misiones · PLANTILLA DEL ALUMNO
 * =====================================================================
 *  PARTE 1 (UD3): recorrer el array de misiones y pintar cada tarjeta.
 *  PARTE 2 (UD4): filtro por GET y aceptación por POST.
 *  PARTE 3 (UD5): página solo para miembros; contador de visitas en sesión.
 */

declare(strict_types=1);

require_once __DIR__ . '/inc/funciones.php';
require_once __DIR__ . '/inc/sesion.php';

iniciarSesion();
$aventurero = requiereLogin();
$misiones   = require __DIR__ . '/inc/datos_misiones.php';
$_SESSION['zurron'] ??= [];

// TODO (Parte 3): suma 1 a $_SESSION['visitas'] en cada carga del tablón.

// ---- POST: aceptar una misión --------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO (Parte 2):
    //  1. Lee $_POST['id'] y busca la misión con buscarMision().
    //  2. Si no existe → mensajeFlash('Esa misión no existe en el tablón.')
    //  3. Si ya está en $_SESSION['zurron'] → mensaje de aviso, sin duplicar.
    //  4. Si no → añade el id a $_SESSION['zurron'] y mensaje de confirmación.
    //  5. Redirige (header Location + exit) para no reenviar el formulario con F5.
}

// ---- GET: filtros ---------------------------------------------------------
$rango     = leerRangoFiltro($_GET);
$oroMinimo = leerOroMinimo($_GET);
$visibles  = filtrarMisiones($misiones, $rango, $oroMinimo);
$flash     = leerFlash();

$titulo = 'Tablón de misiones';
require __DIR__ . '/inc/cabecera.php';
?>
<h1>Tablón de misiones</h1>
<!-- TODO (Parte 3): muestra la hora de entrada y las visitas de esta sesión. -->
<?php if ($flash !== null): ?><p class="aviso"><?= e($flash) ?></p><?php endif; ?>

<form method="get" action="tablon.php" class="filtros">
    <label>Rango
        <select name="rango">
            <option value="">Todos</option>
            <?php foreach (RANGOS as $r): ?>
                <option value="<?= $r ?>" <?= $r === $rango ? 'selected' : '' ?>><?= $r ?> · <?= etiquetaRango($r) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Recompensa mínima
        <input type="number" name="oro" min="0" max="10000" step="10" value="<?= $oroMinimo ?>">
    </label>
    <button type="submit">Filtrar</button>
</form>

<p><?= count($visibles) ?> de <?= count($misiones) ?> misiones.</p>

<div class="misiones">
<?php
/*
 * TODO (Parte 1): recorre $visibles con foreach y pinta por cada misión:
 *
 *   <article class="mision CLASE">            CLASE = claseRango(rango)
 *     <span class="rango">B · Experto</span>   rango · etiquetaRango(rango)
 *     <h2>Título</h2>
 *     <p class="nota">Zona · 3 días</p>        "1 día" en singular
 *     <p>Descripción</p>
 *     <p class="oro">650 mo</p>                formatearOro()
 *     ... botón "Aceptar misión" (Parte 2) o "✔ En tu zurrón" si ya está aceptada
 *   </article>
 *
 * Escapa con e() todo texto que venga de los datos.
 * El botón es un formulario POST con un <input type="hidden" name="id">.
 * Si no queda ninguna misión, muestra "Ninguna misión cumple el filtro."
 */
?>
</div>
<?php require __DIR__ . '/inc/pie.php'; ?>
