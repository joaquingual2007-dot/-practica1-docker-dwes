<?php
/**
 * =====================================================================
 *  ficha.php · Forja de Héroes · UD2 · Reto evaluable
 * =====================================================================
 *  Autor/a: Joaquín Gutiérrez Almagro · Variante: A (Guerrera)
 *
 *  Estructura OBLIGATORIA (como en el paso 09 del caso guiado):
 *   1) directiva strict_types
 *   2) carga de inc/heroe.php con require_once y __DIR__
 *   3) CÁLCULOS: lógica pura en bloque PHP inicial (sin HTML)
 *   4) PRESENTACIÓN: HTML con valores incrustados
 *
 *  REGLAS: Sin if, else, switch, match, bucles, arrays ni funciones propias.
 *  Todo resuelto con operadores, ternarios anidados (condición ? a : b),
 *  operador Elvis (?:) y fusión de null (??).
 */

// 1. Directiva de tipos estrictos
declare(strict_types=1);

// 2. Carga del fichero de configuración y datos del héroe
require_once __DIR__ . '/inc/heroe.php';

// =====================================================================
// 3. CÁLCULOS (Lógica del servidor)
// =====================================================================

// R2 · Conversión de tipos (casting explícito)
$fuerza        = (int) $fuerzaTxt;
$destreza      = (int) $destrezaTxt;
$inteligencia  = (int) $inteligenciaTxt;
$constitucion  = (int) $constitucionTxt;
$experiencia   = (int) $experienciaTxt;
$vidaActual    = (int) $vidaActualTxt;
$oro           = (float) $oroTxt;

// R3 · Progresión
$nivel         = intdiv($experiencia, XP_POR_NIVEL) + 1;
$xpEnNivel     = $experiencia % XP_POR_NIVEL;
$xpParaSubir   = XP_POR_NIVEL - $xpEnNivel;
$pctNivel      = ($xpEnNivel / XP_POR_NIVEL) * 100;

// R4 · Vida y combate
$vidaMax       = VIDA_BASE + $constitucion * $nivel * MULT_VIDA;
$pctVida       = ($vidaActual / $vidaMax) * 100;

// Fórmulas de daño y estadística especial según variante (ternario anidado)
// - Variante A (Guerrera):  daño = fuerza² / 10 + nivel × 2  | crítico (%) = (fuerza mód 5) × 3
// - Variante B (Hechicero): daño = inteligencia × 3 + nivel² / 4 | maná (pm) = inteligencia × 10 + (XP mód 100)
// - Variante C (Pícara):    daño = destreza × 2 + nivel^1.5 | emboscada = (nivel × destreza) mód 7 + 1
$danio = (CLASE_HEROE === 'Guerrera')
    ? ($fuerza ** 2) / 10 + $nivel * 2
    : ((CLASE_HEROE === 'Hechicero')
        ? $inteligencia * 3 + ($nivel ** 2) / 4
        : $destreza * 2 + ($nivel ** 1.5));

$etiquetaStatEspecial = (CLASE_HEROE === 'Guerrera')
    ? 'Bonus de crítico'
    : ((CLASE_HEROE === 'Hechicero')
        ? 'Maná'
        : 'Turno de emboscada');

$valorStatEspecialTxt = (CLASE_HEROE === 'Guerrera')
    ? (($fuerza % 5) * 3) . ' %'
    : ((CLASE_HEROE === 'Hechicero')
        ? ($inteligencia * 10 + ($experiencia % 100)) . ' pm'
        : (($nivel * $destreza) % 7 + 1) . '.º turno');

$poder         = (int) round($danio * $nivel);
$comparacion   = $poder <=> PODER_RIVAL;
$veredicto     = ($comparacion === 1)
    ? 'Ventaja: ¡a la carga!'
    : (($comparacion === 0)
        ? 'Empate: combate igualado'
        : 'Desventaja: mejor retirarse');

// R5 · Estado y decisiones
$estado        = ($vidaActual > 0)
    ? (($pctVida < 35) ? 'En peligro' : 'En pie')
    : 'K.O.';

$puedeAscender  = ($nivel >= 5 && $pctVida >= 50) ? 'Sí' : 'No';
$necesitaPocion = ($pctVida < 40 || !$esVeterano) ? 'Sí' : 'No';
$insignia       = $esVeterano ? 'VETERANO' : 'NOVATO';

// R6 · Barras de progreso (bloques llenos █ y vacíos ░)
$bloquesVida   = (int) round($pctVida / 100 * BLOQUES_BARRA);
$barraVida     = str_repeat('█', $bloquesVida) . str_repeat('░', BLOQUES_BARRA - $bloquesVida);

$bloquesXp     = (int) round($pctNivel / 100 * BLOQUES_BARRA);
$barraXp       = str_repeat('█', $bloquesXp) . str_repeat('░', BLOQUES_BARRA - $bloquesXp);

// R7 · Textos y salida
$nombreSeguro      = htmlspecialchars($nombreHeroe);
$apodoHeroe        = $apodo ?? 'Sin apodo';
$lemaHeroe         = $lema ?: 'Sin lema (todavía)';
$oroFormateado     = number_format($oro, 2, ',', '.') . ' mo';
$danioFormateado   = number_format($danio, 1, ',', '.');
$pctVidaFormateado = number_format($pctVida, 1, ',', '.');

// Registro de combate construido con concatenación compuesta (.=) en al menos 3 pasos
$registroCombate  = '[' . date('H:i:s') . '] ' . $nombreSeguro . ' (nv. ' . $nivel . ') ';
$registroCombate .= 'se prepara para combatir contra ' . NOMBRE_RIVAL . ' (poder ' . PODER_RIVAL . '). ';
$registroCombate .= 'Resultado táctico: ' . $veredicto;

// Crónica de tres líneas con sintaxis heredoc interpolando variables
$claseHeroe = CLASE_HEROE;
$cronica = <<<CRONICA
La combatiente {$nombreSeguro}, formidable {$claseHeroe} de nivel {$nivel}, vigila atenta la forja.
Aún necesita acumular {$xpParaSubir} puntos de experiencia para alcanzar el siguiente nivel.
Tras sus recientes lances y fatigas, su estado actual de combate es: {$estado}.
CRONICA;

// Datos de entorno para pie de página
$fechaGeneracion = date('d/m/Y H:i:s');
$versionPhp      = PHP_VERSION;

// =====================================================================
// 4. PRESENTACIÓN: HTML con valores incrustados
// =====================================================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ficha de <?= $nombreSeguro ?></title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <!-- R1: Insignia VETERANO/NOVATO con la etiqueta LARGA con echo -->
    <span class="insignia"><?php echo $insignia; ?></span>
    <!-- A partir de aquí, se usa la etiqueta CORTA de salida (&lt;?= variable ?&gt;) -->
    <h1><?= $nombreSeguro ?></h1>
    <p class="subtitulo"><?= CLASE_HEROE ?> · «<?= $apodoHeroe ?>» · <?= $lemaHeroe ?></p>

    <h2>Estadísticas</h2>
    <div class="rejilla">
        <div class="stat"><div class="etq">Nivel</div><div class="val"><?= $nivel ?></div></div>
        <div class="stat"><div class="etq">Vida</div><div class="val"><?= $vidaActual ?> / <?= $vidaMax ?></div></div>
        <div class="stat"><div class="etq">Daño</div><div class="val"><?= $danioFormateado ?></div></div>
        <div class="stat"><div class="etq"><?= $etiquetaStatEspecial ?></div><div class="val"><?= $valorStatEspecialTxt ?></div></div>
        <div class="stat"><div class="etq">Poder</div><div class="val"><?= $poder ?></div></div>
        <div class="stat"><div class="etq">Oro</div><div class="val"><?= $oroFormateado ?></div></div>
    </div>
    <table>
        <tr><th>Fuerza</th><th>Destreza</th><th>Inteligencia</th><th>Constitución</th></tr>
        <tr><td><?= $fuerza ?></td><td><?= $destreza ?></td><td><?= $inteligencia ?></td><td><?= $constitucion ?></td></tr>
    </table>

    <h2>Progreso</h2>
    <p class="barra vida">VIDA <?= $barraVida ?> · <?= $pctVidaFormateado ?> %</p>
    <p class="barra xp">XP&nbsp;&nbsp; <?= $barraXp ?> · <?= $xpEnNivel ?>/<?= XP_POR_NIVEL ?> (faltan <?= $xpParaSubir ?>)</p>

    <h2>Estado y decisiones</h2>
    <table>
        <tr><td>Estado</td><td><?= $estado ?></td></tr>
        <tr><td>¿Puede ascender de rango? (nivel ≥ 5 y vida ≥ 50 %)</td><td><?= $puedeAscender ?></td></tr>
        <tr><td>¿Necesita poción? (vida &lt; 40 % o no veterano)</td><td><?= $necesitaPocion ?></td></tr>
        <tr><td>Rival: <?= NOMBRE_RIVAL ?> (poder <?= PODER_RIVAL ?>)</td><td><?= $comparacion ?> → <?= $veredicto ?></td></tr>
    </table>

    <h2>Crónica</h2>
    <pre><?= $cronica ?></pre>
    <p><em><?= $registroCombate ?></em></p>

    <h2>Modo depuración</h2>
    <pre><?php
echo 'Tipo de $experienciaTxt: ' . get_debug_type($experienciaTxt) . PHP_EOL;
echo 'Tipo de $experiencia:    ' . get_debug_type($experiencia) . PHP_EOL . PHP_EOL;

echo 'var_dump($oro):' . PHP_EOL;
var_dump($oro);
echo PHP_EOL . 'var_dump($pctVida):' . PHP_EOL;
var_dump($pctVida);
echo PHP_EOL . 'var_dump($esVeterano):' . PHP_EOL;
var_dump($esVeterano);
echo PHP_EOL . 'var_dump($apodo):' . PHP_EOL;
var_dump($apodo);

echo PHP_EOL . 'Comparación $experienciaTxt == $experiencia:  ';
var_dump($experienciaTxt == $experiencia);
echo 'Comparación $experienciaTxt === $experiencia: ';
var_dump($experienciaTxt === $experiencia);
?></pre>

    <footer>Generado el <?= $fechaGeneracion ?> · PHP <?= $versionPhp ?> ·
        <a href="diagnostico.php">Diagnóstico del servidor</a></footer>
</main>
</body>
</html>
