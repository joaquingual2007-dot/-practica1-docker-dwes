<?php
/**
 * =====================================================================
 *  diagnostico.php · Panel de directivas del servidor          [CE 2.f]
 * =====================================================================
 *  Autor/a: Joaquín Gutiérrez Almagro
 *
 *  Requisito R9 del enunciado:
 *   - strict_types como primera instrucción.
 *   - Leer con ini_get las 6 directivas de forja.ini y comprobar
 *     error_reporting() === E_ALL.
 *   - Mostrar qué ficheros .ini adicionales ha cargado PHP.
 *   - Cambiar date.timezone con ini_set, mostrar la hora antes y después,
 *     el valor devuelto por ini_set y restaurarla con ini_restore.
 *   - Intentar cambiar short_open_tag con ini_set y mostrar el resultado.
 */

declare(strict_types=1);

// ---------------------------------------------------------------------
// 1. Lectura de directivas configuradas en php/forja.ini con ini_get()
// ---------------------------------------------------------------------
$displayErrors  = ini_get('display_errors');
$errorRepIni    = ini_get('error_reporting');
$dateTimezone   = ini_get('date.timezone');
$shortOpenTag   = ini_get('short_open_tag');
$exposePhp      = ini_get('expose_php');
$memoryLimit    = ini_get('memory_limit');

// Comprobación de error_reporting() frente a la constante E_ALL
$nivelErrores = error_reporting();
$esEAll       = ($nivelErrores === E_ALL);

// Ficheros ini cargados por el motor de PHP
$iniPrincipal = php_ini_loaded_file() ?: '(ninguno)';
$iniExtra     = php_ini_scanned_files() ?: '(ninguno)';

// ---------------------------------------------------------------------
// 2. Modificación de directivas en tiempo de ejecución (ini_set)
// ---------------------------------------------------------------------
$horaAntes          = date('H:i:s');
$retornoIniSetTz    = ini_set('date.timezone', 'Asia/Tokyo');
$horaDespuesTokio   = date('H:i:s');
ini_restore('date.timezone');
$horaRestaurada     = date('H:i:s');

// Intento de modificar short_open_tag en tiempo de ejecución (devolverá false)
$retornoShortOpenTag = ini_set('short_open_tag', '1');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Diagnóstico del servidor · Forja de Héroes</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <h1>Diagnóstico del servidor</h1>
    <p class="subtitulo">PHP <?= PHP_VERSION ?> · Sistema: <?= PHP_OS ?></p>

    <h2>Directivas configuradas en php/forja.ini</h2>
    <table>
        <tr><th>Directiva</th><th>Valor (ini_get)</th><th>Por qué</th></tr>
        <tr>
            <td><code>display_errors</code></td>
            <td><?= var_export($displayErrors, true) ?></td>
            <td>Muestra errores en el navegador durante el desarrollo para depurar con rapidez.</td>
        </tr>
        <tr>
            <td><code>error_reporting</code></td>
            <td><?= var_export($errorRepIni, true) ?></td>
            <td>Reporta todos los errores (E_ALL). Actual: <?= $nivelErrores ?> (¿Es E_ALL? <?= $esEAll ? 'Sí' : 'No' ?>).</td>
        </tr>
        <tr>
            <td><code>date.timezone</code></td>
            <td><?= var_export($dateTimezone, true) ?></td>
            <td>Asegura que funciones como date() utilicen el huso horario local de España.</td>
        </tr>
        <tr>
            <td><code>short_open_tag</code></td>
            <td><?= var_export($shortOpenTag, true) ?></td>
            <td>Desactiva la etiqueta corta &lt;? por portabilidad y evitar conflictos con XML.</td>
        </tr>
        <tr>
            <td><code>expose_php</code></td>
            <td><?= var_export($exposePhp, true) ?></td>
            <td>Oculta la versión de PHP en las cabeceras HTTP de respuesta para mayor seguridad.</td>
        </tr>
        <tr>
            <td><code>memory_limit</code></td>
            <td><?= var_export($memoryLimit, true) ?></td>
            <td>Límite máximo de memoria por script para prevenir el agotamiento de recursos.</td>
        </tr>
    </table>

    <h2>Ficheros de configuración .ini</h2>
    <table>
        <tr><td>Fichero .ini principal</td><td><code><?= $iniPrincipal ?></code></td></tr>
        <tr><td>Ficheros .ini adicionales (conf.d)</td><td><code><?= $iniExtra ?></code></td></tr>
    </table>

    <h2>Cambio en tiempo de ejecución (ini_set)</h2>
    <table>
        <tr><th>Acción / Prueba</th><th>Resultado</th></tr>
        <tr>
            <td>Hora inicial (<code><?= $dateTimezone ?></code>)</td>
            <td><?= $horaAntes ?></td>
        </tr>
        <tr>
            <td>Cambio con <code>ini_set('date.timezone', 'Asia/Tokyo')</code></td>
            <td>Hora resultante: <?= $horaDespuesTokio ?></td>
        </tr>
        <tr>
            <td>Valor devuelto por <code>ini_set()</code> al cambiar zona</td>
            <td><?= var_export($retornoIniSetTz, true) ?> (devuelve el valor anterior)</td>
        </tr>
        <tr>
            <td>Hora tras ejecutar <code>ini_restore('date.timezone')</code></td>
            <td><?= $horaRestaurada ?> (restaurado a la zona original)</td>
        </tr>
        <tr>
            <td>Intento: <code>ini_set('short_open_tag', '1')</code></td>
            <td><?= var_export($retornoShortOpenTag, true) ?> (false: directiva PHP_INI_PERDIR / PHP_INI_SYSTEM no modificable con ini_set)</td>
        </tr>
    </table>

    <footer><a href="ficha.php">← Volver a la ficha</a></footer>
</main>
</body>
</html>
