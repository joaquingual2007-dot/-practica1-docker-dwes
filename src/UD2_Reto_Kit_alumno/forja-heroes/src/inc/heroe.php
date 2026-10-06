<?php
/**
 * =====================================================================
 *  inc/heroe.php · Reglas del juego y datos del héroe          [CE 2.c]
 * =====================================================================
 *  Autor/a: Joaquín Gutiérrez Almagro · Variante: A (Guerrera)
 *
 *  Este fichero contiene SOLO PHP. No lleva etiqueta de cierre (?>)
 *  para evitar envíos accidentales de espacios en blanco o saltos de línea.
 */

// 1. Directiva strict_types (primera instrucción del fichero)
declare(strict_types=1);

// 2. Reglas comunes (constantes con const)
const XP_POR_NIVEL  = 400;
const VIDA_BASE     = 50;
const BLOQUES_BARRA = 20;

// =====================================================================
// 3. DATOS DE TU VARIANTE
//    (Activa por defecto: VARIANTE A. Si tu profesor te asignó la B o la C,
//     solo tienes que comentar el bloque A y descomentar el tuyo).
// =====================================================================

// --- VARIANTE A · Guerrera (ACTIVA) ----------------------------------
const CLASE_HEROE   = 'Guerrera';
const MULT_VIDA     = 3;
const NOMBRE_RIVAL  = 'Ogro de las Cavernas';
define('PODER_RIVAL', 200);

$nombreHeroe     = 'Brunhilda <Martillo de Hierro>';
$apodo           = null;
$lema            = '';
$fuerzaTxt       = '17';
$destrezaTxt     = '9';
$inteligenciaTxt = '6';
$constitucionTxt = '15';
$experienciaTxt  = '2350';
$vidaActualTxt   = '118';
$oroTxt          = '1520.75';
$esVeterano      = true;

/*
// --- VARIANTE B · Hechicero ------------------------------------------
const CLASE_HEROE   = 'Hechicero';
const MULT_VIDA     = 1;
const NOMBRE_RIVAL  = 'Dragón de Obsidiana';
define('PODER_RIVAL', 650);

$nombreHeroe     = 'Aldric <el Arcano>';
$apodo           = null;
$lema            = '';
$fuerzaTxt       = '8';
$destrezaTxt     = '11';
$inteligenciaTxt = '19';
$constitucionTxt = '10';
$experienciaTxt  = '3120';
$vidaActualTxt   = '41';
$oroTxt          = '987.4';
$esVeterano      = false;
*/

/*
// --- VARIANTE C · Pícara ----------------------------------------------
const CLASE_HEROE   = 'Pícara';
const MULT_VIDA     = 2;
const NOMBRE_RIVAL  = 'Sombra Gemela';
define('PODER_RIVAL', 236);

$nombreHeroe     = 'Vex <Sin Nombre>';
$apodo           = null;
$lema            = '';
$fuerzaTxt       = '11';
$destrezaTxt     = '18';
$inteligenciaTxt = '12';
$constitucionTxt = '12';
$experienciaTxt  = '1890';
$vidaActualTxt   = '96';
$oroTxt          = '2045.6';
$esVeterano      = true;
*/
