<?php
/**
 * =====================================================================
 *  inc/funciones.php · Funciones del tablón · PLANTILLA DEL ALUMNO
 * =====================================================================
 *  PARTE 1 (UD3): decisión con match, recorridos con foreach, arrays.
 *  PARTE 2 (UD4): funciones propias con tipos, parámetros y retorno.
 *
 *  Cada función tiene un "return provisional" para que la web funcione
 *  desde el primer día. Sustitúyelo por tu código y borra el TODO.
 *  NO cambies los nombres ni las firmas: el resto de páginas las usan.
 */

declare(strict_types=1);

/** Rangos válidos, de menor a mayor dificultad. (Dado) */
const RANGOS = ['E', 'D', 'C', 'B', 'A', 'S'];

/** Escapa texto para pintarlo en HTML. (Dado: úsalo SIEMPRE al pintar datos) */
function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

/**
 * PARTE 1 · Nombre del rango.
 * E Novato · D Aprendiz · C Veterano · B Experto · A Élite · S Leyenda
 * TODO: usa match. Pregunta para la memoria: ¿pones default? ¿por qué?
 */
function etiquetaRango(string $rango): string
{
    return '¿?'; // TODO
}

/**
 * PARTE 1 · Clase CSS según dificultad.
 * E y D → 'facil' · C y B → 'media' · A y S → 'dificil'
 * TODO: usa match con varios valores por brazo.
 */
function claseRango(string $rango): string
{
    return ''; // TODO
}

/**
 * PARTE 1 · Formatea monedas de oro: 5000 → "5.000 mo".
 * TODO: busca number_format en el manual de PHP.
 */
function formatearOro(int $oro): string
{
    return $oro . ' mo'; // TODO
}

/**
 * PARTE 2 · Filtra misiones por rango (null = todos) y recompensa mínima.
 * TODO: recorre con foreach y construye un array nuevo. No uses array_filter
 *       todavía: queremos ver el bucle.
 */
function filtrarMisiones(array $misiones, ?string $rango, int $oroMinimo): array
{
    return $misiones; // TODO
}

/**
 * PARTE 2 · Busca una misión por su id ('M05'). Devuelve null si no existe.
 */
function buscarMision(array $misiones, string $id): ?array
{
    return null; // TODO
}

/**
 * PARTE 2 · Suma la recompensa de las misiones cuyos ids se indican.
 * Reutiliza buscarMision(). Un id inexistente no suma nada.
 */
function totalRecompensa(array $misiones, array $ids): int
{
    return 0; // TODO
}

/**
 * PARTE 2 · Lee el rango de $_GET['rango'] y lo valida.
 * Devuelve el rango en MAYÚSCULAS si está en RANGOS; si no viene o no es
 * válido, null. Nunca te fíes de la URL: el usuario puede escribir lo que quiera.
 */
function leerRangoFiltro(array $get): ?string
{
    return null; // TODO
}

/**
 * PARTE 2 · Lee $_GET['oro'] y lo valida como entero entre 0 y 10000.
 * Si no viene o no es válido, 0.
 * TODO: filter_var con FILTER_VALIDATE_INT y opciones min_range / max_range.
 */
function leerOroMinimo(array $get): int
{
    return 0; // TODO
}
